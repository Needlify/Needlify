<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use ArrayIterator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator
{
    public const ITEMS_PER_PAGE = 50;

    private int $total;
    private array $data;
    private int $count;
    private int $pages;

    public function __construct($query, bool $fetchJoinCollection = true)
    {
        parent::__construct($query, $fetchJoinCollection);
        $this->total = $this->count();
        $this->data = iterator_to_array(parent::getIterator());
        $this->count = count($this->data);
        $this->pages = ceil($this->count / $this->total);
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
        return $this->pages;
    }

    public function getItemsPerPage() {
        return $this->getQuery()->getMaxResults();
    }

    public function getCurrentPage() {
        return ceil($this->getQuery()->getFirstResult() / $this->getItemsPerPage()) + 1;
    }

    public function hasNextPage() {
        if($this->getCurrentPage() >= $this->getPages()) {
            return false;
        }

        return true;
    }

    public function hasPreviousPage() {
        if($this->getCurrentPage() <= 1) {
            return false;
        }

        return true;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator([
            "data" => $this->getData(),
            "pagination" => [
                "total" => $this->getTotal(),
                "count" => $this->getCount(),
                "items_per_page" => $this->getItemsPerPage(),
                "total_pages" => $this->getPages(),
                "current_page" => $this->getCurrentPage(),
                "has_next_page" => $this->hasNextPage(),
                "has_previous_page" => $this->hasPreviousPage()
            ]
        ]);
    }

}
