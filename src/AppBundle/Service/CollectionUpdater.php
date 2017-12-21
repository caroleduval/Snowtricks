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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function compareCollections(Trick $trick, $listPhotos, $listVideos)
    {
        foreach ($listPhotos as $photo) {
            if (false === $trick->getPhotos()->contains($photo)) {
                $this->em->remove($photo);
            }
        }

        foreach ($listVideos as $video) {
            if (false === $trick->getVideos()->contains($video)) {
                $this->em->remove($video);
            }
        }
    }
}
