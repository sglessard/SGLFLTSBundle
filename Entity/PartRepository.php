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
 * PartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PartRepository extends EntityRepository
{
     /*
     * Retrieve Parts (custom find)
     *
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieve($queryBuilder = false)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.identification', 'ASC')
            ->addOrderBy('p.name', 'ASC')
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve opened Parts
     *
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveOpened($queryBuilder = false)
    {
        $query = $this->retrieve(true)
            ->select('p, pp, c')
            ->innerjoin('p.project','pp')
            ->innerjoin('pp.client','c')
            ->where('p.closed_at is null')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('p.identification', 'ASC')
            ->addOrderBy('p.name', 'ASC');

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve Parts with project and client data
     *
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveWithProjectClient($queryBuilder = false)
    {
        $query = $this->retrieve(true);

        $query->select('p,pp,c')
            ->innerjoin('p.project', 'pp')
            ->innerjoin('pp.client', 'c')
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('p.identification', 'ASC')
            ->addOrderBy('p.name', 'ASC');
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * Retrieve Parts by project, with bills and works
     *
     * @param integer $id_project
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveOpenedByProjectWithTaskWorkBill($id_project, $queryBuilder = false)
    {
        $query = $this->retrieveOpened(true);

        $query->select('p,b,t,w')
            ->leftjoin('p.bills', 'b')
            ->leftjoin('p.tasks', 't')
            ->leftjoin('t.works','w')
            ->where('p.closed_at is null')
            ->andWhere('p.project = :id_project')
            ->orderBy('p.started_at', 'ASC')
            ->setParameter(':id_project',$id_project);
        ;

        return $this->dispatch($query, $queryBuilder);
    }

        /*
     * Retrieve Parts by project, with bills and works
     *
     * @param integer $id_project
     * @param bool $queryBuilder (return query builder only)
     * @return Doctrine Collection or Query Builder
     */
    public function retrieveByProjectWithTaskWorkBill($id_project, $queryBuilder = false)
    {
        $query = $this->retrieveOpenedByProjectWithTaskWorkBill($id_project, true);

        $query->select('p,b,t,w')
            ->where('p.project = :id_project')
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /**
     * Retrieve parts with recent works
     *
     * @param int $limit
     * @param bool $queryBuilder
     * @return mixed
     */
    public function retrieveLatestWorkedOn($limit=5, $queryBuilder = false)
    {
        $query = $this->retrieve(true);

        $query->select('DISTINCT p,pp,c')
            ->innerjoin('p.project', 'pp')
            ->innerjoin('pp.client', 'c')
            ->innerjoin('p.tasks', 't')
            ->innerjoin('t.works', 'w')
            ->orderBy('w.worked_at', 'DESC')
            ->addOrderBy('w.started_at', 'DESC')
            ->setMaxResults($limit);
        ;

        return $this->dispatch($query, $queryBuilder);
    }

    /*
     * dispatch
     *
     * @param Query Builder $query
     * @param boolean  $queryBuilder (return query builder only)
     *
     * @return Doctrine Collection or Query Builder
     */
    private function dispatch($query, $queryBuilder) {
        if ($queryBuilder) {
            return $query;
        } else {
            return $query->getQuery()->getResult();
        }
    }
}
