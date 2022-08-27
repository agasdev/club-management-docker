<?php

namespace App\Packages\Player\Application\DTO;

use App\Packages\Common\Application\DTO\PaginationDto;

class PlayerCollectionDto
{
    public function __construct(
        /** @var PlayerDto[] $players */
        public array $players,
        public PaginationDto $paginationDto
    )
    {
    }

    public static function createEmpty(?int $page = 1): self
    {
        return new self(
            [],
            new PaginationDTO(0, 1, $page ?? 1)
        );
    }
}