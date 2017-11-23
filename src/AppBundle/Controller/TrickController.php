<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TrickController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $listTricks = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Trick')
            ->findAll()
        ;
        return $this->render('Trick/index.html.twig', array(
        'listTricks' => $listTricks
    ));
    }
    /**
     * @Route("/trick/{id}", requirements={"id" = "\d+"})
     */
    public function viewAction(Trick $trick)
    {
        return $this->render('Trick/view.html.twig', array(
            'trick' => $trick
        ));
    }
}