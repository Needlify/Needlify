<?php

/*
 * This file is part of the Needlify project.
 * Copyright (c) Needlify <https://needlify.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

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
        $this->data = $this->getQuery()->getResult();
        $this->count = count($this->data);
        $this->pages = ceil($this->count / $this->total);
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getSerializedData() {
        return [
            "data" => $this->getData(),
            'pagination' => [
                "total" => $this->getTotal(),
                "count" => $this->getCount(),
                "items_per_page" => 50,
                // "current_page" => $this->getPages(),
                "total_pages" => $this->getPages()
            ]
        ];
    }
}


// namespace App\Repository\RiumSearch;

// use JMS\Serializer\Annotation\Expose;
// use JMS\Serializer\Annotation\Groups;
// use JMS\Serializer\Annotation\Type;
// use JMS\Serializer\Annotation\VirtualProperty;

// class Pagination
// {
//     const DEFAULT_ITEMS_PER_PAGE = self::MIN_ITEMS_PER_PAGE;
//     const MIN_ITEMS_PER_PAGE = 15;
//     const AVG_ITEMS_PER_PAGE = 30;
//     const MAX_ITEMS_PER_PAGE = 50;

//     /**
//      * @var int
//      * @type("int")
//      * @Groups({"pagination"})
//      */
//     private int $currentItems = 0;

//     /**
//      * @var int
//      * @Type("int")
//      * @Groups({"pagination"})
//      */
//     private int $totalItems = 0;

//     /**
//      * @var int
//      * @Type("int")
//      * @Groups({"pagination"})
//      */
//     private int $itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;

//     /**
//      * @var int
//      * @Type("int")
//      * @Groups({"pagination"})
//      */
//     private int $currentPage = 1;

//     /**
//      * @return int
//      */
//     public function getCurrentItems(): int
//     {
//         return $this->currentPage;
//     }

//     /**
//      * @param int $currentItems
//      *
//      * @return $this
//      */
//     public function setCurrentItems(int $currentItems): Pagination
//     {
//         $this->currentItems = $currentItems;
//         return $this;
//     }

//     /**
//      * @return int
//      */
//     public function getTotalItems(): int
//     {
//         return $this->totalItems;
//     }

//     /**
//      * @param int $totalItems
//      *
//      * @return $this
//      */
//     public function setTotalItems(int $totalItems): Pagination
//     {
//         $this->totalItems = $totalItems;
//         return $this;
//     }

//     /**
//      * @return int
//      */
//     public function getItemsPerPage(): int
//     {
//         return $this->itemsPerPage;
//     }

//     /**
//      * @param int $itemsPerPage
//      *
//      * @return $this
//      */
//     public function setItemsPerPage(int $itemsPerPage): Pagination
//     {
//         $this->itemsPerPage = $itemsPerPage;
//         return $this;
//     }

//     /**
//      * @return int
//      */
//     public function getCurrentPage(): int
//     {
//         return $this->currentPage;
//     }

//     /**
//      * @param int $currentPage
//      * @return $this
//      */
//     public function setCurrentPage(int $currentPage): Pagination
//     {
//         $this->currentPage = $currentPage;
//         return $this;
//     }

//     /**
//      * @VirtualProperty
//      * @Groups({"pagination"})
//      * @return bool
//      */
//     public function hasPreviousPage() : bool
//     {
//         if($this->currentPage == 1){
//             return false;
//         }
//         return true;
//     }

//     /**
//      * @VirtualProperty
//      * @Groups({"pagination"})
//      * @return bool
//      */
//     public function hasNextPage() : bool
//     {
//         if($this->currentPage >= $this->getTotalPages()){
//             return false;
//         }
//         return true;
//     }

//     /**
//      * @virtualProperty
//      * @Groups({"pagination"})
//      * @return int
//      */
//     public function getTotalPages() : int
//     {
//         if($this->getItemsPerPage() > 0) {
//             return ceil($this->getTotalItems() / $this->getItemsPerPage()) ;
//         }
//         return 1;
//     }

//     /**
//      * @virtualProperty
//      * @Groups({"pagination"})
//      * @return int
//      */
//     public function getOffset() : int
//     {
//         return ($this->currentPage - 1) * $this->itemsPerPage;
//     }

//     /**
//      * Un autre nom parlant pour ce getter
//      *
//      * @return int
//      */
//     public function getLimit() : int
//     {
//         return $this->itemsPerPage;
//     }
// }
