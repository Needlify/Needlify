<?php

/*
 * This file is part of the Needlify project.
 *
 * Copyright (c) Needlify <https://needlify.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Admin;

use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;

class CourseLinker
{
    public function __construct(
        private LessonRepository $lessonRepository,
        private EntityManagerInterface $em,
    ) {
    }

    public function link(array $orderedId)
    {
        /*
         * Malheureusement, c'est la seule méthode que j'ai trouvé pour réordonner
         * plusieurs éléments dans être contraint par la contrainte d'unicité d'SQL
         */

        // Reset les liens en BDD pour éviter les contraintes d'unicité
        for ($i = 0; $i < count($orderedId) - 1; ++$i) {
            $current = $this->lessonRepository->findOneBy(['id' => $orderedId[$i]]);
            $next = $this->lessonRepository->findOneBy(['id' => $orderedId[$i + 1]]);

            $next->resetLink();
            $current->resetLink();
        }

        $this->em->flush();

        // Maintenant on remet les liens comme il faut
        for ($i = 0; $i < count($orderedId) - 1; ++$i) {
            $current = $this->lessonRepository->findOneBy(['id' => $orderedId[$i]]);
            $next = $this->lessonRepository->findOneBy(['id' => $orderedId[$i + 1]]);

            $next->setPrevious($current);
            $current->setNext($next);
        }

        $this->em->flush();
    }
}
