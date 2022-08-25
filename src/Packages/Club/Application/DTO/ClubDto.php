<?php

namespace App\Packages\Club\Application\DTO;

use App\Packages\Club\Domain\Entity\Club;

class ClubDto
{
    public ?string $id;
    public ?string $name;
    public ?string $city;
    public ?string $country;
    public ?int $budget;

    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $city = null,
        ?string $country = null,
        ?int $budget = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->budget = $budget;
    }

    public static function assemble(Club $club): self
    {
        return new self(
            $club->getId()->value(),
            $club->getName()->value(),
            $club->getCity()->value(),
            $club->getCountry()->value(),
            $club->getBudget()->value()
        );
    }

    public static function createEmpty(): self
    {
        return new self();
    }
}