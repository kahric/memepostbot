<?php

namespace App\Controller;

use App\Entity\MemeUser;
use App\Entity\Upvote;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use App\Entity\Meme;
use App\Form\AddMemeType;

class MemeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $repository = $this->getDoctrine()->getRepository(Meme::class);
        $memes = $repository->findBy(['uploaded' => false]);

        usort($memes, function ($a, $b) {
            return $a->getUpvotes()->count() <=> $b->getUpvotes()->count();
        });

        return $this->render('meme/index.html.twig', [
            'memes' => $memes,
            'controller_name' => 'MemeController',
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/meme/add", name="meme_add", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function add(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $meme = new Meme();
        $meme->setUploaded(false);
        $meme->setCreatedBy($this->getUser());

        $form = $this->createForm(AddMemeType::class, $meme);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            try {
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('app.path.upload_destination'),
                    $fileName
                );
            } catch (FileException $e) {
                echo $e->getMessage();
                exit();
                // ... handle exception if something happens during file upload
            }

            $meme -> setImage($this->getParameter('app.path.meme_images') . "/" . $fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($meme);
            $entityManager->flush();

            return $this->redirectToRoute('meme_view', ['id' => $meme->getId()]);
        }

        return $this->render('meme/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/meme/{id}", name="meme_view", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function view(Request $request, $id) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $meme_repository = $this->getDoctrine()->getRepository(Meme::class);
        $vote_repository = $this->getDoctrine()->getRepository(Upvote::class);

        $meme = $meme_repository->find($id);
        $vote = $vote_repository->findOneBy([
            'created_by' => $this->getUser(),
            'meme' => $meme
        ]);

        if(!$meme) {
            return $this->redirectToRoute('app_index');
        }

        return $this->render('meme/view.html.twig', [
            'meme' => $meme,
            'vote' => $vote
        ]);
    }

    /**
     * @Route("/meme/upvote/{id}", name="meme_vote", methods={"POST|GET"})
     * @return Response
     */
    public function upvote($id) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $meme_repository = $this->getDoctrine()->getRepository(Meme::class);
        $upvote_repository = $this->getDoctrine()->getRepository(Upvote::class);
        $entityManager = $this->getDoctrine()->getManager();

        /** @var MemeUser $user */
        $user = $this->getUser();

        $meme = $meme_repository->find($id);

        if(!$meme) {
            return new JsonResponse([
                'status' => false,
                'message' => "Meme with given ID not found."
            ]);
        }

        $already_upvoted = $upvote_repository->findOneBy(['meme' => $meme]);

        if($already_upvoted) {
            $entityManager->remove($already_upvoted);
        } else {
            $upvote = new Upvote();
            $upvote->setCreatedBy($user);
            $upvote->setMeme($meme);
            $entityManager->persist($upvote);
        }

        $entityManager->flush();

        return new JsonResponse([
            'status' => true,
            'revoke' => $already_upvoted ? true : false,
            'count' => $meme->getUpvotes()->count()
        ]);
    }


    /**
     * @Route("/queue", name="memes_queue", methods={"GET"})
     * @return Response
     */
    public function queue() {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $repository = $this->getDoctrine()->getRepository(Meme::class);
        $memes = $repository->findBy(['uploaded' => false]);

        usort($memes, function ($a, $b) {
            return $b->getUpvotes()->count() <=> $a->getUpvotes()->count();
        });

        return $this->render('meme/queue.html.twig', [
            'controller_name' => 'MemeController',
            'memes' => $memes,
            'user' => $this->getUser()
        ]);

    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
