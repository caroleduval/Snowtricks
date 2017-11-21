<?php

namespace AppBundle\Controller;

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
        return $this->render('default/index.html.twig', array(
        'listTricks' => $listTricks
    ));
    }
}