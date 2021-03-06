<?php

namespace BackBundle\Repository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends \Doctrine\ORM\EntityRepository
{
    public function myFindOneBy($slug)
    {
        $q = $this->createQueryBuilder("p")
            ->join("p.translations", "t")
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug);

        $q = $q->getQuery();

        return $q->getOneOrNullResult();
    }


    public function MyFindAllDesc()
    {
        $q = $this->createQueryBuilder("p")
            ->join("p.translations", "t")
            ->orderBy('t.slug', 'asc');

        $q = $q->getQuery();

        return $q->getResult();
    }
}
