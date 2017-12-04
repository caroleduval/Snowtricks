<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Trick;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommentRepository extends EntityRepository
{
    public function getComments(Trick $trick, $page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('c')
            ->leftJoin('c.author', 'u')
            ->addSelect('u')
            ->leftJoin('u.photo', 'p')
            ->addSelect('p')
            ->orderBy('c.dateCrea', 'DESC')
            ->where('c.trick=:trick_id')
            ->setParameter('trick_id',$trick->getId())
            ->getQuery()
        ;

        $query
            // On définit l'annonce à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbPerPage)
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($query, true);
    }
}
