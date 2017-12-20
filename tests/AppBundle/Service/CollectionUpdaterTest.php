<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Video;
use AppBundle\Entity\Trick;
use AppBundle\Service\CollectionUpdater;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionUpdaterTest
 * @package Tests\AppBundle\Service
 */
class CollectionUpdaterTest extends TestCase
{
    private $em;

    protected function setUp()
    {
        $this->em = $this->createMock(EntityManager::class);
        parent::setUp();
    }

    // this test applies to SetVideoCollection too as the method is working the same way
    public function testSetPhotoCollection()
    {
        $cu= new CollectionUpdater($this->em);

        $trick = new Trick();
        $photo1 = new Photo();
        $trick->addPhoto($photo1);
        $photo2 = new Photo();
        $trick->addPhoto($photo2);
        $photo3 = new Photo();
        $trick->addPhoto($photo3);

        $this->assertEquals($trick->getPhotos(), $cu->setPhotoCollection($trick));
    }

    public function testCompareCollections()
    {
        $this->em->expects ($this->exactly(3))->method('remove');

        $cu= new CollectionUpdater($this->em);

        $photo1 = new Photo();
        $photo2 = new Photo();
        $photo3 = new Photo();

        $video1 = new Video();
        $video2 = new Video();
        $video3 = new Video();

        $listPhotos=[$photo1,$photo2,$photo3];
        $listVideos=[$video1,$video2,$video3];

        $trick = new Trick();

        $trick->addPhoto($photo1);
        $trick->addPhoto($photo2);
        $trick->addVideo($video3);

        $cu->compareCollections($trick,$listPhotos,$listVideos);
    }
}