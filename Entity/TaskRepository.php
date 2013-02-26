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
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends EntityRepository
{
    /*
     * Retrieve Tasks (custom find)
     *
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieve($queryBuilder = false)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.rank', 'ASC')
        ;

        return $this->dispatch($query, $queryBuilder);
    }

        /*
     * Retrieve Tasks (custom find)
     *
     * @param integer $id_part
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveWithWorksByPart($id_part,$queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('t,w')
            ->leftjoin('t.works','w')
            ->where('t.part = :id_part')
            ->orderBy('t.rank', 'ASC')
            ->setParameter('id_part',$id_part);
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve Tasks by part
     *
     * @param integer $id_part
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveByPartWithWorksToBill($id_part,$queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('t,w,b')
            ->innerjoin('t.works','w')
            ->leftjoin('w.bill','b')
            ->where('w.do_not_bill = false')
            ->andWhere('t.part = :id_part')
            ->orderBy('t.rank', 'ASC')
            ->addOrderBy('w.worked_at', 'ASC')
            ->addOrderBy('w.started_at', 'ASC')
            ->setParameter('id_part',$id_part);
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve available works to bill
     *
     * @param integer $id_bill
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveByPartAvailableWorksToBill($id_part,$id_bill,$queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('t,w')
            ->innerjoin('t.works','w')
            ->leftjoin('w.bill','b')
            ->where('w.do_not_bill = false')
            ->andWhere('t.part = :id_part')
            ->andWhere('b = :id_bill or b is null')
            ->orderBy('t.rank', 'ASC')
            ->addOrderBy('w.worked_at', 'ASC')
            ->addOrderBy('w.started_at', 'ASC')
            ->setParameters(array('id_part'=>$id_part, 'id_bill'=>$id_bill));
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve Tasks by bill with Work
     *
     * @param integer $id_bill
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveByBillWithWorks($id_bill,$queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('w,b,t')
            ->innerjoin('t.works','w')
            ->innerjoin('w.bill','b')
            ->where('b = :id_bill')
            ->orderBy('t.rank', 'ASC')
            ->addOrderBy('w.worked_at', 'ASC')
            ->addOrderBy('w.started_at', 'ASC')
            ->setParameter('id_bill',$id_bill);
        ;

        return $this->dispatch($query, $queryBuilder);
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
    private function dispatch($query, $queryBuilder,$single=false) {
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
