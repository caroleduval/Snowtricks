<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trick;
use AppBundle\Form\Type\TrickType;
use AppBundle\Service\MessageBiblio;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\CollectionUpdater;


/**
 * Class TrickController
 * @package AppBundle\Controller
 */
class TrickController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(EntityManagerInterface $em, Request $request)
    {
        $listTricks = $em->getRepository(Trick::class)
            ->findAll();

        return $this->render('Trick/index.html.twig', array(
            'listTricks' => $listTricks
        ));
    }
    /**
     * @Route("/trick/{slug}/{page}", name="trick_view",requirements={"page":"\d+"})
     */
    public function viewAction(Trick $trick,$page=1,Request $request, MessageBiblio $mb)
    {
        $nbitems=count($trick->getPhotos())+ count ($trick->getVideos());
        $message=$mb->messageCreator($trick);
        return $this->render('Trick/view.html.twig', array(
            'trick' => $trick,
            'page'=>$page,
            'count'=> $nbitems,
            'message'=>$message
        ));
    }
    /**
     * @Route("/ajouter_un_trick", name="trick_add")
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Request $request, EntityManagerInterface $em)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($trick);
            $em->flush();

            $this->addFlash("info", "Le trick a bien été enregistré.");

            return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
        }

        // Si visite initiale (requête GET) ou formulaire invalide
        return $this->render('Trick/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/modifier_un_trick/{slug}", name="trick_edit")
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, EntityManagerInterface $em, Trick $trick, CollectionUpdater $cu)
    {
        if (null === $trick) {
            throw new NotFoundHttpException("Le trick demandé n'existe pas.");
        }
        $listPhotos = $cu->setPhotoCollection($trick);
        $listVideos = $cu->setVideoCollection($trick);

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $form->getData();
            $cu->comparePhotoCollection($trick, $listPhotos);
            $cu->compareVideoCollection($trick, $listVideos);
            $em->flush();

            $this->addFlash("info", "Le trick a bien été enregistré.");
            return $this->redirectToRoute('trick_view', array('slug' => $trick->getSlug()));
        }
        return $this->render('Trick/add.html.twig', array(
            'trick'=> $trick,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/supprimer_un_trick/{slug}", name="trick_delete")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction(Request $request, EntityManagerInterface $em, Trick $trick)
    {
        if (null === $trick) {
            throw new NotFoundHttpException("Le trick demandé n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($trick);
            $em->flush();
            $this->addFlash('info', 'Le trick a bien été supprimé.');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('Trick/delete.html.twig', array(
            'trick'=> $trick,
            'form' => $form->createView(),
        ));
    }
    public function listAction($limit, EntityManagerInterface $em)
    {
        $listTricks = $em->getRepository('AppBundle:Trick')
            ->findBy(
                array(),
                array('lastUpdate' => 'desc'),
                $limit,
                0
            );
        return $this->render('Trick/list.html.twig', array(
            'listTricks' => $listTricks
        ));
    }
}
