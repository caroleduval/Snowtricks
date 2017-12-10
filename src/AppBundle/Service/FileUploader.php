<?php

namespace AppBundle\Service;

use AppBundle\Entity\Photo;

class FileUploader
{
    public function preUpload(Photo $photo)
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $photo->getFile()) {
            return;
        }
        $file=$photo->getFile();
        $photo->setType($file->guessExtension());
        $photo->setAlt($file->getClientOriginalName());
    }
    public function upload(Photo $photo)
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $photo->getFile()) {
            return;
        }
        // Si on avait un ancien fichier (attribut tempFilename non null), on le supprime
        if (null !== $photo->getTempFilename()) {
            $oldFile = $this->getUploadRootDir().'/'.$photo->getId().'.'.$photo->getTempFilename();
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $photo->getFile()->move(
            $this->getUploadRootDir(),
            $photo->getId().'.'.$photo->getType()
        );
    }
    public function preRemoveUpload(Photo $photo)
    {
        $photo->setTempFilename($this->getUploadRootDir().'/'.$photo->getId().'.'.$photo->getType());
    }
    public function removeUpload(Photo $photo)
    {
        if (file_exists($photo->getTempFilename())) {
            unlink($photo->getTempFilename());
        }
    }
    public function getUploadDir()
    {
        return 'upload/photos';
    }
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
}