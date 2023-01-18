<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Symfony\Component\Uid\Uuid;
use App\Entity\NewsletterAccount;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interface\DashboardRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<NewsletterAccount>
 *
 * @method NewsletterAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterAccount[]    findAll()
 * @method NewsletterAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterAccountRepository extends ServiceEntityRepository implements DashboardRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterAccount::class);
    }

    public function save(NewsletterAccount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NewsletterAccount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getIsVerifiedById(Uuid $uuid)
    {
        return $this->createQueryBuilder('a')
            ->select('a.isVerified')
            ->where('a.id = :id')
            ->setParameter('id', $uuid->toBinary())
            ->getQuery()
            ->getSingleResult()['isVerified'];
    }
}
