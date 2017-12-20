<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Video;
use AppBundle\Entity\Trick;
use AppBundle\Service\BiblioMessager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BiblioMessagerTest extends KernelTestCase
{
    protected $bm;

    public function setUp()
    {
        $this->bm = new BiblioMessager();
    }

    public function testMessageBiblio00()
    {
        $trick = new Trick();
        $bm=$this->bm;

        $this->assertSame("Pas encore de contenu multimédia pour ce trick.", $bm->messageCreator($trick));
    }
    public function testMessageBiblio10()
    {
        $trick = new Trick();
        $photo= new Photo();
        $trick->addPhoto($photo);
        $bm=$this->bm;

        $this->assertSame("Ce trick est illustré par 1 photo.", $bm->messageCreator($trick));
    }
    public function testMessageBiblio01()
    {
        $trick = new Trick();
        $video= new Video();
        $trick->addVideo($video);
        $bm=$this->bm;

        $this->assertSame("Ce trick est illustré par 1 vidéo.", $bm->messageCreator($trick));
    }
    public function testMessageBiblio11()
    {
        $trick = new Trick();
        $photo= new Photo();
        $trick->addPhoto($photo);
        $video= new Video();
        $trick->addVideo($video);
        $bm=$this->bm;

        $this->assertSame("Ce trick est illustré par 1 photo et 1 vidéo.", $bm->messageCreator($trick));
    }
    public function testMessageBiblio23()
    {
        $trick = new Trick();
        $photo1= new Photo();
        $trick->addPhoto($photo1);
        $photo2= new Photo();
        $trick->addPhoto($photo2);
        $video1= new Video();
        $trick->addVideo($video1);
        $video2= new Video();
        $trick->addVideo($video2);
        $video3= new Video();
        $trick->addVideo($video3);
        $bm=$this->bm;

        $this->assertSame("Ce trick est illustré par 2 photos et 3 vidéos.", $bm->messageCreator($trick));
    }
}