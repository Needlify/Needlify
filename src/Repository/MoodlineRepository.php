<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Moodline;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interface\DashboardRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Moodline>
 *
 * @method Moodline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moodline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moodline[]    findAll()
 * @method Moodline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoodlineRepository extends ServiceEntityRepository implements DashboardRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moodline::class);
    }

    /**
     * @codeCoverageIgnore
     */
    public function add(Moodline $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function remove(Moodline $entity, bool $flush = false): void
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
}
