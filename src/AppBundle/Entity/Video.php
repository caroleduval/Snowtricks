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

    /**
     * @ORM\PrePersist() // Les trois événement suivant s’exécute avant que l’entité soit enregister
     * @ORM\PreUpdate()
     * @ORM\PreFlush()
     */
    public function extractIdentif()
    {
        $url = $this->getUrl();  // on récupère l’url

        if (preg_match("#iframe#", $url))
        {
            if (preg_match("#//www.youtube.com/#", $url))
            {
                $this->youtubeIframe($url);
            }
            else if((preg_match("#//www.dailymotion.com/#", $url)))
            {
                $this->dailymotionIframe($url);
            }
            else if((preg_match("#//vimeo.com/#", $url)))
            {
                $this->vimeoIframe($url);
            }
        }
        else
        {
            if (preg_match("#//www.youtube.com/#", $url))
            {
                $this->youtubeId($url);
            }
            else if((preg_match("#//www.dailymotion.com/#", $url)))
            {
                $this->dailymotionId($url);
            }
            else if((preg_match("#//vimeo.com/#", $url)))
            {
                $this->vimeoId($url);
            }
        }
    }

    private function youtubeId($url)
    {
        //ex : https://www.youtube.com/watch?v=92rXe1XJMuI
        $tableaux = explode("=", $url);

        $this->setIdentif($tableaux[1]);
        $this->setType('youtube');
    }

    private function youtubeIframe($url)
    {
        //ex :  <iframe width="560" height="315"
        //      src="https://www.youtube.com/embed/92rXe1XJMuI"
        //      frameborder="0" allowfullscreen></iframe>
        $tableau1 = explode("embed/", $url);
        $tableau2 = explode("\"", $tableau1[1]);

        $this->setIdentif($tableau2[0]);
        $this->setType('youtube');
    }

    private function dailymotionId($url)
    {
        // ex : http://www.dailymotion.com/video/x4wh6to_la-planete-des-singes-3-video-virale_shortfilms
        $cas = explode("/", $url);

        $idb = $cas[4];  // On récupère la 4ème partie qui nous intéresse
        $bp = explode("_", $idb);
        $id = $bp[0];

        $this->setIdentif($id);
        $this->setType('dailymotion');
    }

    private function dailymotionIframe($url)
    {
        //ex :  <iframe frameborder="0" width="480" height="270"
        //      src="//www.dailymotion.com/embed/video/x38yzyc"
        //      allowfullscreen></iframe>

        $tableau1 = explode("video/", $url);
        $tableau2 = explode("\" ", $tableau1[1]);

        $this->setIdentif($tableau2[0]);
        $this->setType('dailymotion');
    }

    private function vimeoId($url)
    {
        // ex : https://vimeo.com/61350155
        $tableaux = explode("/", $url);
        $id = $tableaux[count($tableaux)-1];

        $this->setIdentif($id);
        $this->setType('vimeo');

    }

    private function vimeoIframe($url)
    {
        //ex :  <iframe src="https://player.vimeo.com/video/45834437?title=0&byline=0&portrait=0"
        //      width="640" height="360" frameborder="0"
        //      webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        if (preg_match("#/?title#", $url)){
            $tableau1 = explode("video/", $url);
            $tableau2 = explode("?", $tableau1[1]);

            $this->setIdentif($tableau2[0]);
            $this->setType('vimeo');
        }
        else {
            $tableau1 = explode("video/", $url);
            $tableau2 = explode("\"", $tableau1[1]);

            $this->setIdentif($tableau2[0]);
            $this->setType('vimeo');
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
        $video = "<iframe width='100%' height='200px' src='".$this->url()."'  frameborder='0'  allowfullscreen></iframe>";
        return $video;
    }
}
