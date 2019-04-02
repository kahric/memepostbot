<?php

namespace App\Command;

use App\Entity\Meme;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;


class PostMemeCommand extends Command
{
    protected static $defaultName = 'app:post-meme';
    protected $path;

        /////// CONFIG ///////
    private $username;
    private $password;
    private static $debug = false;
    private static $truncatedDebug = true;

    /**
     * @var ContainerInterface
     */
    private $container;

    protected function configure()
    {
        $this
            ->setDescription('Posts the oldest (by upload) meme with most upvotes.')
            ->addArgument('meme_id', InputArgument::OPTIONAL, "ID of a meme that should be posted.")
            ->addOption('reupload', 'r', InputOption::VALUE_NONE, 'If the meme is already uploaded, post it again using this option.')
            ->addOption('ig_verbose', 'd', InputOption::VALUE_NONE, 'Debug mode for IG API')
        ;
    }

    public function __construct(ContainerInterface $container, string $path)
    {
        $this->path = $path;
        $this->container = $container;
        $this->username = getenv('IG_USERNAME');
        $this->password = getenv('IG_PASSWORD');
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if($input->getOption('ig_verbose')) {
            self::$debug = true;
        }

        $ig = new \InstagramAPI\Instagram(self::$debug, self::$truncatedDebug);
        try {
            $ig->login($this->username, $this->password);
        } catch (\Exception $e) {
            echo 'Something went wrong: '.$e->getMessage()."\n";
            exit(0);
        }

        $re = $this->container->get('doctrine')->getRepository(Meme::class);
        $em = $this->container->get('doctrine')->getManager();

        $meme_id = $input->getArgument('meme_id');

        /** @var Meme $meme */
        if($meme_id) {
            $meme = $re->find($meme_id);
        } else {
            $memes = $re->findBy(['uploaded' => false]);
            if(count($memes) == 0) {
                $io->error("No memes in queue found!");
                exit();
            }

            usort($memes, function ($a, $b) {
                return $b->getUpvotes()->count() <=> $a->getUpvotes()->count();
            });

            $meme = $memes[0];
        }


        if(!$meme) {
            $io->error("Meme with ID of \"" . $meme_id . "\" could not be found.");
            exit();
        }

        if(!$meme->getUploaded()) {
            $meme->setUploaded(true);
            $meme->setUploadedAt(new \DateTime('now'));
            $em->persist($meme);
            $em->flush();
        } else {
            if($input->getOption('reupload')) {
                $io->warning("Reuploading a meme!");
            } else {
                $io->error("This meme is already uploaded. use the '--reupload' option to reupload this image.");
                exit();
            }
        }

        $image_path = str_replace("\\", "/", $this->path . "/public" . $meme->getImage());

        $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($image_path);
        $ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $meme->getCaption()]);

        $io->success('Image has been posted successfully!');
    }



}
