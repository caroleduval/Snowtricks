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
     * @Assert\Regex(
     *     pattern="#(http|https)://(www.youtube.com|www.dailymotion.com|vimeo.com)/#",
     *     match=true,
     *     message="L'url doit correspondre à l'url d'une vidéo Youtube, DailyMotion ou Vimeo"
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

    private function youtubeId($url)
    {
        $tableaux = explode("=", $url);  // découpe l’url en deux  avec le signe ‘=’

        $this->setIdentif($tableaux[1]);  // ajoute l’identifiant à l’attribut identif
        $this->setType('youtube');  // signale qu’il s’agit d’une video youtube et l’inscrit dans l’attribut $type
    }

    private function dailymotionId($url)
    {
        $cas = explode("/", $url); // On sépare la première partie de l'url des 2 autres

        $idb = $cas[4];  // On récupère la partie qui nous intéressent

        $bp = explode("_", $idb);  // On sépare l'identifiant du reste

        $id = $bp[0]; // On récupère l'identifiant

        $this->setIdentif($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('dailymotion'); // signale qu’il s’agit d’une video youtube et l’inscrit dans l’attribut $type
    }

    private function vimeoId($url)
    {
        $tableaux = explode("/", $url);  // on découpe l’url grâce au « / »

        $id = $tableaux[count($tableaux)-1];  // on reticent la dernière partie qui contient l’identifiant

        $this->setIdentif($id);  // ajoute l’identifiant à l’attribut identif

        $this->setType('vimeo');  // signale qu’il s’agit d’une video vimeo et l’inscrit dans l’attribut $type

    }

    /**
     * @ORM\PrePersist() // Les trois événement suivant s’exécute avant que l’entité soit enregister
     * @ORM\PreUpdate()
     * @ORM\PreFlush()
     */
    public function extractIdentif()
    {
        $url = $this->getUrl();  // on récupère l’url

        if (preg_match("#(http|https)://www.youtube.com/#", $url))  // Si c’est une url Youtube on execute la fonction correspondante
        {
            $this->youtubeId($url);
        }
        else if((preg_match("#(http|https)://www.dailymotion.com/#", $url))) // Si c’est une url Dailymotion on execute la fonction correspondante
        {
            $this->dailymotionId($url);
        }
        else if((preg_match("#(http|https)://vimeo.com/#", $url))) // Si c’est une url Vimeo on execute la fonction correspondante
        {
            $this->vimeoId($url);
        }

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
        $video = "<iframe width='100%' height='100%' src='".$this->url()."'  frameborder='0'  allowfullscreen></iframe>";
        return $video;
    }
}
