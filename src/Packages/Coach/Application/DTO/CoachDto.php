<?php

namespace App\Packages\Coach\Application\DTO;

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

    public function __construct(
        string $id = null,
        string $name= null,
        string $surname= null,
        string $dateOfBirth= null,
        string $city= null,
        string $country= null,
        int $salary= null,
        string $email= null
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
    }

    public static function assemble(Coach $coach): self
    {
        return new self(
            $coach->getId()->value(),
            $coach->getName()->name(),
            $coach->getName()->surname(),
            $coach->getDateOfBirth()->format('d/m/Y'),
            $coach->getCity()->value(),
            $coach->getCountry()->value(),
            $coach->getSalary()->value(),
            $coach->getEmail()->value()
        );
    }


    public static function createEmpty(): self
    {
        return new self();
    }
}