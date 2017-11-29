<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Trick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        $nbPages = ceil(count($listComments) / 10);
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
}