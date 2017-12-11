<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trick;
use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CommentController extends Controller
{
    /**
     * @Route("/trick/{trick_id}/comments/{page}", name="comments_list", requirements={"trick" = "\d+","page" = "\d+"})
     * @ParamConverter("trick", options={"mapping": {"trick_id": "id"}})
     */
    public function indexAction(Trick $trick, $page=1, EntityManagerInterface $em)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
        // Amélioration : utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 10;
        // On récupère notre objet Paginator
        $listComments = $em->getRepository('AppBundle:Comment')
            ->getComments($trick, $page, $nbPerPage)
        ;
        if (count($listComments)==0) {
            $nbPages =1;
        }
        else {
            $nbPages = ceil(count($listComments) / $nbPerPage);
        }
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('Comment/index.html.twig', array(
            'trick' => $trick,
            'listComments' => $listComments,
            'nbPages'     => $nbPages,
            'page'        => $page,
        ));
    }
    /**
     * @Route("/trick/{trick_id}/comments/add", name="comments_add", requirements={"trick" = "\d+"})
     * @ParamConverter("trick", options={"mapping": {"trick_id": "id"}})
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Request $request, EntityManagerInterface $em, Trick $trick)
    {
        $comment = new Comment();
        $comment->setTrick($trick);
        $comment->setAuthor($this->getUser());


        $form = $this->createForm(CommentType::class, $comment);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {$em->persist($comment);
            $em->flush();

            // Remise du formulaire à zéro avant de renvoyer la page mise à jour
            unset($comment);
            unset($form);
            $comment = new Comment();
            $comment->setTrick($trick);
            $form = $this->get('form.factory')->create(CommentType::class, $comment);

            return $this->redirectToRoute('trick_view', array('id' => $trick->getId()));
        }


        // Si visite initiale (requête GET) ou formulaire invalide
        return $this->render('Form/commentForm.html.twig', array(
            'form' => $form->createView(),
            'comment' => $comment
        ));
    }

    public function listAction($limit, EntityManagerInterface $em)
    {
        $listComments = $em->getRepository('AppBundle:Comment')
            ->findBy(
                array(),
                array('dateCrea' => 'desc'),
                $limit,
                0
            );
        return $this->render('Comment/list.html.twig', array(
            'listComments' => $listComments
        ));
    }
}