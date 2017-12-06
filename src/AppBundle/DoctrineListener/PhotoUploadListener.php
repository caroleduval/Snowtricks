<?php

namespace AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Service\FileUploader;
use AppBundle\Entity\Photo;

class PhotoUploadListener
{
    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Photo) {
            return;
        }

        $this->fileUploader->preUpload($entity);
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Photo) {
            return;
        }

        $this->fileUploader->preUpload($entity);
    }
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Photo) {
            return;
        }

        $this->fileUploader->Upload($entity);
    }
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Photo) {
            return;
        }

        $this->fileUploader->Upload($entity);
    }
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Photo) {
            return;
        }

        $this->fileUploader->preRemoveUpload($entity);
    }
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Photo) {
            return;
        }

        $this->fileUploader->removeUpload($entity);
    }
}

