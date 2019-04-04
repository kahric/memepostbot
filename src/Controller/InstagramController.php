<?php

namespace App\Controller;

use App\Entity\Meme;
use Doctrine\ORM\EntityNotFoundException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;

class InstagramController extends AbstractController
{
    private static $username = 'meme.rehab';
    private static $password = 'LosiMemesiDobraGuja1!';
    private static $debug = false;
    private static $truncatedDebug = true;
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @Route("/instagram/post/{meme_id}/{csrf_token}", name="instagram_post")
     */
    public function index($meme_id, $csrf_token)
    {
        var_dump($this->isCsrfTokenValid('instagram_post', $csrf_token));

        if (!$this->isCsrfTokenValid('instagram_post', $csrf_token)) {
            return new Response('Invalid CSRF token.');
        }

        \InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new \InstagramAPI\Instagram(self::$debug, self::$truncatedDebug);
        try {
            $ig->login(self::$username, self::$password);
        } catch (\Exception $e) {
            echo 'Something went wrong: '.$e->getMessage()."\n";
            exit(0);
        }

        $repository = $this->getDoctrine()->getRepository(Meme::class);
        $entity_manager = $this->getDoctrine()->getManager();

        /** @var Meme $meme */
        if($meme_id) {
            $meme = $repository->find($meme_id);
        } else {
            $meme = $repository->findAll()[0];
        }

        if(!$meme) {
            throw new EntityNotFoundException("Meme with ID of \"" . $meme_id . "\" could not be found.");
        }

        if(!$meme->getUploaded()) {
            $image_path = str_replace("\\", "/", $this->path . "/public" . $meme->getImage());
            $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($image_path);
            $ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $meme->getCaption()]);

            $meme->setUploaded(true);
            $meme->setUploadedAt(new \DateTime('now'));
            $entity_manager->persist($meme);
            $entity_manager->flush();
        }

        return $this->redirectToRoute('meme_view', ['id' => $meme_id]);
    }

    /**
     * @Route("/scraper")
     */
    public function scraper() {


        $fileSystem = new Filesystem();
        $scrapes_dir = $this->getParameter('kernel.project_dir') . "/scraper/";

        $json = file_get_contents($scrapes_dir . '1_json.txt');

        $array = json_decode($json);

        $output = "";

        echo "<h1>{$array->name}</h1>";

        foreach($array->childrenItems as $child) {
            echo $line = ($child->children ? '<strong>' : '') . $child->name . ":" . $child->id . ":" . ($child->children ? '1' : '0') . ($child->children ? '</strong>' : '');
            echo "<br>";
            $output .= $line ."\r\n";
        }

        try {
            $output_file = $scrapes_dir  . "categories/" . $array->name . '.txt';
            $fileSystem->dumpFile($output_file, $output);

        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }



        return new Response($output_file);


    }
}
