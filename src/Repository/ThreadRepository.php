<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Thread;
use App\Service\Paginator as ServicePaginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Thread>
 *
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    /**
     * @codeCoverageIgnore
     */
    public function add(Thread $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove(Thread $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithPagination(int $offset = 0)
    {
        // TODO: Refactor this
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.publishedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(ServicePaginator::ITEMS_PER_PAGE);

        $paginator = new ServicePaginator($query->getQuery());

        return $paginator;

        // // TODO Utiliser le paginator custom

        // return [
        //     'total' => $paginator->count(),
        //     'data' => $paginator->getQuery()->getResult(),
        // ];
    }
}
