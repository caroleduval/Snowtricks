<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trick;
use AppBundle\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class TrickController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(EntityManagerInterface $em)
    {
        $listTricks = $em->getRepository(Trick::class)
            ->findAll();

        return $this->render('Trick/index.html.twig', array(
        'listTricks' => $listTricks
    ));
    }
    /**
     * @Route("/trick/{id}", name="trick_view", requirements={"id" = "\d+"})
     */
    public function viewAction(Trick $trick)
    {
        return $this->render('Trick/view.html.twig', array(
            'trick' => $trick
        ));
    }
    /**
     * @Route("/ajouter_un_trick", name="trick_add")
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);


        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($trick);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Trick bien enregistré.');

                return $this->redirectToRoute('trick_view', array('id' => $trick->getId()));
            }

        // Si visite initiale (requête GET) ou formulaire invalide
        return $this->render('Trick/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}