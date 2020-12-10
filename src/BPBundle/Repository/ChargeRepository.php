<?php

namespace BPBundle\Repository;

/**
 * ChargeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChargeRepository extends \Doctrine\ORM\EntityRepository
{
    public function myFindAllByBusinessPlan($bp)
    {
        $q = $this->createQueryBuilder("c")
            ->leftJoin("c.businessPlan", "bp")
            ->addSelect("bp")
            ->where('c.businessPlan = :bp')

            ->setParameter('bp', $bp)
        ;

        $q = $q->getQuery();

        return $q->getResult();

    }
}