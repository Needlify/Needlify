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

use App\Entity\Course;
use App\Entity\Lesson;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interface\DashboardRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Lesson>
 *
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[] findAll()
 * @method Lesson[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository implements DashboardRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function save(Lesson $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lesson $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countAll(): int
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->join('l.course', 'c')
            ->where('l.private = 0')
            ->andWhere('c.private = 0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function lessonExists(Uuid $id)
    {
        return null !== $this->createQueryBuilder('l')
            ->where('l.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getOriginalCourse(Lesson $lesson): ?Course
    {
        $result = $this
            ->getEntityManager()
            ->createQuery('SELECT c FROM App\Entity\Course c JOIN c.lessons l WHERE l.id = :id')
            ->setParameter('id', $lesson->getId()->toBinary())
            ->getOneOrNullResult();

        return $result;
    }
}
