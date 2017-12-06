<?php

namespace AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Service\IframeBuilder;
use AppBundle\Entity\Video;

class VideoCreationListener
{
    /**
     * @var IframeBuilder
     */
    private $iframeBuilder;

    public function __construct(IframeBuilder $iframeBuilder)
    {
        $this->iframeBuilder = $iframeBuilder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Video) {
            return;
        }

        $this->iframeBuilder->analyseUrl($entity);
    }
}
