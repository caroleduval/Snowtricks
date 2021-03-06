<?php

namespace AppBundle\Service;

use AppBundle\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class CollectionUpdater
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    // On injecte l'EntityManager
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function setPhotoCollection(Trick $trick)
    {
        $listPhotos = new ArrayCollection();
        foreach ($trick->getPhotos() as $photo) {
            $listPhotos->add($photo);
        }
        return $listPhotos;
    }
    public function setVideoCollection(Trick $trick)
    {
        $listVideos = new ArrayCollection();
        foreach ($trick->getVideos() as $video) {
            $listVideos->add($video);
        }
        return $listVideos;
    }
    public function comparePhotoCollection(Trick $trick, ArrayCollection $listPhotos)
    {
        foreach ($listPhotos as $photo) {
            if (false === $trick->getPhotos()->contains($photo)) {
                $this->em->remove($photo);
            }
        }
    }
    public function compareVideoCollection(Trick $trick, ArrayCollection $listVideos)
    {
        foreach ($listVideos as $video) {
            if (false === $trick->getVideos()->contains($video)) {
                $this->em->remove($video);
            }
        }
    }
}
