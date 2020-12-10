<?php

namespace BackBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository {

    public function countUser($year = null, $mounth = null, $partner = null) {
        $q = $this->createQueryBuilder("u")
                ->select('COUNT(u)');

        if ($partner) {
            $q->leftJoin("u.partner", "partner")
                    ->where('partner.id = :idPartner')
                    ->setParameter('idPartner', $partner->getId());
        }

        if ($mounth) {
            if (!$year) {
                $year = (new \DateTime())->format("Y");
            }

            $fromDate = date_create($year . '-' . $mounth . '-01');
            $toDate = clone $fromDate;
            $toDate->modify('last day of this month');

            $q->andWhere("u.createdAt >= :from")
                    ->andWhere("u.createdAt <= :to")
                    ->setParameter('from', $fromDate)
                    ->setParameter('to', $toDate);
        } else {
            if ($year) {
                $fromDate = date_create($year . '-' . '01' . '-01');
                $q->andWhere("u.createdAt >= :from")
                        ->setParameter('from', $fromDate);
            }
        }

        $q = $q->getQuery();

        return $q->getSingleScalarResult();
    }

    public function search($data, $page = 0, $max = NULL, $getCount = false, $partner = null) {
        $qb = $this->createQueryBuilder("u");

        if ($getCount) {
            $qb->select('COUNT(u)');
        }

        $qb->join('u.partner', 'p')
                ->orderBy('u.lastLogin', 'DESC');

        $query = isset($data['query']) && $data['query'] ? $data['query'] : null;

        if ($query) {
            $qb
                    ->andWhere('u.username like :query OR u.email like :query OR p.nom like :query')
                    ->setParameter('query', "%" . $query . "%")
            ;
        }

        if ($partner) {
            $qb->andWhere('p.id = :partnerId')
                    ->setParameter('partnerId', $partner->getId());
        }

        if ($max) {
            $preparedQuery = $qb->getQuery()
                    ->setMaxResults($max)
                    ->setFirstResult($page * $max)
            ;
        } else {
            $preparedQuery = $qb->getQuery();
        }

        return $getCount ? $preparedQuery->getSingleScalarResult() : $preparedQuery->getResult();
    }

    public function findAllUserCreatedWithoutPurchase($date) {

        $from = new \DateTime($date->format("Y-m-d") . " 00:00:00");
        $to = new \DateTime($date->format("Y-m-d") . " 23:59:59");

        $q = $this->createQueryBuilder("u")
                ->leftJoin("u.carts", "c")
                ->where('u.createdAt BETWEEN :from AND :to')
                ->andWhere('c.id is null')
                ->setParameter('from', $from)
                ->setParameter('to', $to);

        $q = $q->getQuery();

        return $q->getResult();
    }

}
