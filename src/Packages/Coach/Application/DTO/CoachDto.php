<?php

namespace App\Packages\Coach\Application\DTO;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Coach\Domain\Entity\Coach;

class CoachDto
{
    public ?string $id;
    public ?string $name;
    public ?string $surname;
    public ?string $dateOfBirth;
    public ?string $city;
    public ?string $country;
    public ?int $salary;
    public ?string $email;
    public ?ClubDto $club;

    public function __construct(
        string $id = null,
        string $name= null,
        string $surname= null,
        string $dateOfBirth= null,
        string $city= null,
        string $country= null,
        int $salary= null,
        string $email= null,
        ClubDto $club = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->dateOfBirth = $dateOfBirth;
        $this->city = $city;
        $this->country = $country;
        $this->salary = $salary;
        $this->email = $email;
        $this->club = $club;
    }

    public static function assemble(Coach $coach): self
    {
        return new self(
            $coach->getId()->value(),
            $coach->getName()->name(),
            $coach->getName()->surname(),
            $coach->getDateOfBirth()->format('Y-m-d'),
            $coach->getCity()->value(),
            $coach->getCountry()->value(),
            $coach->getSalary()?->value(),
            $coach->getEmail()->value(),
            $coach->getClub() ? ClubDto::assemble($coach->getClub()) : null
        );
    }


    public static function createEmpty(): self
    {
        return new self();
    }
}