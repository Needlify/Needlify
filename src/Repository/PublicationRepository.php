<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Publication;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Publication>
 *
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    /**
     * @codeCoverageIgnore
     */
    public function add(Publication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove(Publication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithPagination(int $offset = 0, ?string $selector = null, ?string $id = null)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.publishedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(50);

        switch ($selector) {
            case 'topic':
                $query
                    ->andWhere('p.topic = :topic')
                    ->setParameter('topic', Uuid::fromRfc4122($id)->toBinary());
                break;
            case 'tag':
                $query
                    ->join('p.tags', 't')
                    ->andWhere('t.id = :tag')
                    ->setParameter('tag', Uuid::fromRfc4122($id)->toBinary());
                break;
        }

        $paginator = new Paginator($query->getQuery());

        $total = count($paginator);

        return [
            'total' => $total,
            'data' => $paginator->getQuery()->getResult(),
        ];
    }

//    /**
//     * @return Publication[] Returns an array of Publication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Publication
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
