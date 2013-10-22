<?php

/*
 * This file is part of the SGLFLTSBundle package.
 *
 * (c) Simon Guillem-Lessard <s.g.lessard@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SGL\FLTSBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BillRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BillRepository extends EntityRepository
{
    /*
     * Retrieve Bills (custom find)
     *
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieve($queryBuilder = false)
    {
        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->orderBy('b.number', 'DESC')
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve latest Bills (from Date)
     *
     * @param \DateTime $more_recent_than
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveLatest($more_recent_than, $queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('b,p,pp,w')
            ->innerjoin('b.part','p')
            ->innerjoin('p.project','pp')
            ->leftjoin('b.works','w')
            ->where('b.billed_at > :date')
            ->setParameter('date', $more_recent_than->format('Y-m-d'))
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve latest Bills (fixed limit)
     *
     * @param integer $limit
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveFixedLatest($limit=5, $queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('b,p,pp')
            ->innerjoin('b.part','p')
            ->innerjoin('p.project','pp')
            ->setMaxResults($limit)
        ;

        return $this->dispatch($query, $queryBuilder);
    }

     /*
     * Find one with works
     *
     * @param integer $id_bill
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function findWithPartProjectWorks($id_bill, $queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('b,p,pp,w')
            ->innerjoin('b.part','p')
            ->innerjoin('p.project','pp')
            ->leftjoin('b.works','w')
            ->where('b = :id_bill')
            ->setParameter('id_bill', $id_bill);

        return $this->dispatch($query, $queryBuilder, true);
    }

    public function findWithPartProjectClientWorks($id_bill, $queryBuilder = false)
    {
        $query = $this->findWithPartProjectWorks($id_bill, true)
            ->select('b,p,pp,w,c')
            ->innerjoin('pp.client','c');

        return $this->dispatch($query, $queryBuilder, true);
    }

     /*
     * Find one with associated works
     * Cascade is important to fetch every entities in one query (bill > part > task > works)
     * @see https://gist.github.com/sglessard/7101357
     *
     * @param integer $id_bill
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function findWithPartTasksWorks($id_bill, $queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('b,p,t,w')
            ->innerjoin('b.part','p')
            ->innerjoin('p.tasks','t')
            ->innerjoin('t.works','w')
            ->where('w.bill = :id_bill')
            ->setParameter('id_bill', $id_bill);

        return $this->dispatch($query, $queryBuilder, true);
    }


    /*
     * Find bills by part
     *
     * @param integer $id_part
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveByPartWithProject($id_part, $queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('b,p,pp')
            ->innerjoin('b.part','p')
            ->innerjoin('p.project','pp')
            ->where('p.id = :id_part')
            ->setParameter('id_part', $id_part);

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Find next bill number
     *
     * @return integer $number
     */
    public function findNextNumber() {
        $query = $this->retrieve(true)
          ->select('b.number + 1 as number')
          ->setMaxResults(1);

        $next =  $this->dispatch($query, false, true);
        return isset($next['number']) ? $next['number'] : 1;
    }

    /*
     * dispatch
     *
     * @param Query Builder $query
     * @param boolean  $queryBuilder (return query builder only)
     * @param boolean  $single
     *
     * @return Doctrine Collection or Query Builder
     */
    private function dispatch($query, $queryBuilder, $single=false) {
        if ($queryBuilder) {
            return $query;
        } else {

            $results = $query->getQuery()->getResult();

            if ($single && isset($results[0])) {
                return $results[0];
            } else {
                return $results;
            }
        }
    }
}
