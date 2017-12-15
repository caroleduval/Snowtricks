<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks // Permet d’utiliser des événements
 */
class Video
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="identif", type="string", length=255)
     */
    private $identif;


    /**
     *
     * @Assert\Regex(
     *     pattern="#(www.youtube.com|www.dailymotion.com|vimeo.com)#",
     *     match=true,
     *     message="L'url doit correspondre à l'url d'une vidéo Youtube, DailyMotion ou Vimeo."
     * )
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Trick", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Video
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set identif
     *
     * @param string $identif
     *
     * @return Video
     */
    public function setIdentif($identif)
    {
        $this->identif = $identif;

        return $this;
    }

    /**
     * Get identif
     *
     * @return string
     */
    public function getIdentif()
    {
        return $this->identif;
    }

    /**
     * Set trick
     *
     * @param \AppBundle\Entity\Trick $trick
     *
     * @return Video
     */
    public function setTrick(\AppBundle\Entity\Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * Get trick
     *
     * @return \AppBundle\Entity\Trick
     */
    public function getTrick()
    {
        return $this->trick;
    }

    private  function url()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getIdentif()); // on récupère son identifiant

        if($control == 'youtube')
        {
            $embed = "https://www.youtube-nocookie.com/embed/".$id;
            return $embed;
        }
        else if ($control == 'dailymotion')
        {
            $embed = "https://www.dailymotion.com/embed/video/".$id;
            return $embed;
        }
        else if($control == 'vimeo')
        {
            $embed = "https://player.vimeo.com/video/".$id;
            return $embed;
        }
    }

    public function image()
    {
        $control = $this->getType();  // on récupère le type de la vidéo
        $id = strip_tags($this->getIdentif()); // on récupère son identifiant

        if($control == 'youtube')
        {
            $image = 'https://img.youtube.com/vi/'. $id. '/hqdefault.jpg';
            return $image;
        }
        else if ($control == 'dailymotion')
        {
            $image = 'https://www.dailymotion.com/thumbnail/150x120/video/'. $id. '' ;
            return $image;
        }
        else if($control == 'vimeo')
        {
            $hash = unserialize(file_get_contents("https://vimeo.com/api/v2/video/".$id.".php"));
            $image = $hash[0]['thumbnail_small'];
            return $image;
        }
    }

    public function video()
    {
        $video = "<iframe width='100%' height='200px' src='".$this->url()."'  frameborder='0'  allowfullscreen></iframe>";
        return $video;
    }
}
