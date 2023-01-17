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

use App\Entity\Tag;
use App\Entity\Topic;
use App\Model\Paginator;
use App\Entity\Classifier;
use App\Entity\Publication;
use App\Enum\ClassifierType;
use Symfony\Component\Uid\Uuid;
use App\Exception\ExceptionCode;
use App\Exception\ExceptionFactory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

    public function findAllWithPagination(int $page, Uuid $id)
    {
        /** @var Topic|Tag $classifier */
        $classifier = $this->getEntityManager()->find(Classifier::class, $id);

        if (!$classifier) {
            throw ExceptionFactory::throw(BadRequestHttpException::class, ExceptionCode::RESSOURCE_NOT_FOUND, "Classifier with id '%s' not found", [$id->toRfc4122()]);
        }

        $query = $this->createQueryBuilder('p')
            ->orderBy('p.publishedAt', 'DESC');

        switch ($classifier->getType()) {
            case ClassifierType::TOPIC:
                $query
                    ->andWhere('p.topic = :topic')
                    ->setParameter('topic', $classifier->getId()->toBinary());
                break;
            case ClassifierType::TAG:
                $query
                    ->join('p.tags', 't')
                    ->andWhere('t.id = :tag')
                    ->setParameter('tag', $classifier->getId()->toBinary());
                break;
        }

        $paginator = new Paginator($query->getQuery(), $page);

        return $paginator;
    }
}
