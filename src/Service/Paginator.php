<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator
{
    public const ITEMS_PER_PAGE = 50;

    private int $total;
    private array $data;
    private int $count;
    private int $totalpages;
    private int $page;

    public function __construct(QueryBuilder|Query $query, int $page = 1, bool $fetchJoinCollection = true)
    {
        $query->setFirstResult(($page - 1) * self::ITEMS_PER_PAGE);
        $query->setMaxResults(self::ITEMS_PER_PAGE);

        parent::__construct($query, $fetchJoinCollection);
        $this->total = $this->count();
        $this->data = iterator_to_array(parent::getIterator());
        $this->count = count($this->data);
        $this->page = $page;

        try {
            $this->totalpages = ceil($this->total / self::ITEMS_PER_PAGE);
        } catch (\DivisionByZeroError $e) {
            $this->totalpages = 0;
        }
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPages(): int
    {
        return $this->totalpages;
    }

    public function getItemsPerPage()
    {
        return $this->getQuery()->getMaxResults();
    }

    public function getCurrentPage()
    {
        return $this->page;
    }

    public function hasNextPage()
    {
        if ($this->getCurrentPage() >= $this->getPages()) {
            return false;
        }

        return true;
    }

    public function hasPreviousPage()
    {
        if ($this->getCurrentPage() <= 1) {
            return false;
        }

        return true;
    }

    public function getOffset()
    {
        return $this->getQuery()->getFirstResult();
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator([
            'data' => $this->getData(),
            'pagination' => [
                'total' => $this->getTotal(),
                'count' => $this->getCount(),
                'offset' => $this->getOffset(),
                'items_per_page' => $this->getItemsPerPage(),
                'total_pages' => $this->getPages(),
                'current_page' => $this->getCurrentPage(),
                'has_next_page' => $this->hasNextPage(),
                'has_previous_page' => $this->hasPreviousPage(),
            ],
        ]);
    }
}
