<?php

declare(strict_types=1);

namespace App\Packages\Common\Application\DTO;

class PaginationDto
{

    public function __construct(
        public int $numberOfItems,
        public int $numberOfPages,
        public int $currentPage,
    )
    {
    }

    public static function calculateNumberOfPages(int $numberOfItems, int $itemsPerPage): int
    {
        return max((int)ceil($numberOfItems / $itemsPerPage), 1);
    }
}