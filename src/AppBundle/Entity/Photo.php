<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photo
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    /**
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;
    /**
     * @var UploadedFile
     */
    private $file;
        /**
         * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Trick", inversedBy="photos")
         */
    private $trick;

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    private $tempFilename;
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */

    public function preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }
        $this->type = $this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
    }
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {
            return;
        }
        // Si on avait un ancien fichier (attribut tempFilename non null), on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->type
        );
    }
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->type;
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
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
    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getType();
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }
    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * @param UploadedFile $file
     */
    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        if (null !== $this->type) {
            $this->tempFilename = $this->type;
            $this->type = null;
            $this->alt = null;
        }
    }

    /**
     * @return mixed
     */
    public function getTrick()
    {
        return $this->trick;
    }

    /**
     * @param mixed $trick
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
        return $this;
    }
}
