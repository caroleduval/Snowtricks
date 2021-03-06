<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Video;
use AppBundle\Service\IframeBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestIframeBuilder extends KernelTestCase
{
    protected $builder;

    public function setUp()
    {
        self::bootKernel();
        $this->builder = static::$kernel->getContainer()->get('app.service.iframe_builder');
    }

    /**
     * @dataProvider embedForUrl
     */
    public function testIframeBuilder($url, $embed)
    {
        $video = new Video();
        $video->setUrl($url);
        $analyser=$this->builder->youtubeId($video);

        $this->assertSame($embed, $analyser->url())
        ;
    }

    public function embedForUrl()
    {
        return [
            ['https://www.youtube.com/watch?v=SQyTWk7OxSI', "https://www.youtube-nocookie.com/embed/SQyTWk7OxSI"],
            ['<iframe width="560" height="315" src="https://www.youtube.com/embed/SQyTWk7OxSI" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>', "https://www.youtube-nocookie.com/embed/SQyTWk7OxSI"],
            ['http://www.dailymotion.com/video/x63ites', "https://www.dailymotion.com/embed/video/x63ites"],
            ['<iframe frameborder="0" width="480" height="270" src="//www.dailymotion.com/embed/video/x63ites" allowfullscreen></iframe>',"https://www.dailymotion.com/embed/video/x63ites"],
            ['https://vimeo.com/51701785',"https://player.vimeo.com/video/51701785"],
            ['<iframe src="https://player.vimeo.com/video/51701785" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',"https://player.vimeo.com/video/51701785"]
        ];
    }
}