<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Coach\Infrastructure\Persistence\Doctrine\DoctrineCoachRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineCoachRepository::class)]
class Coach
{
    private string $id;
    private string $name;
    private string $surname;
    private int $yearOfBirth;
    private string $city;
    private string $country;
    private int $salary;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private ?Club $club;

    public function __construct(
        string $id,
        string $name,
        string $surname,
        int $yearOfBirth,
        string $city,
        string $country,
        int $salary,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt,
        ?Club $club
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->yearOfBirth = $yearOfBirth;
        $this->city = $city;
        $this->country = $country;
        $this->salary = $salary;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->club = $club;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getYearOfBirth(): ?int
    {
        return $this->yearOfBirth;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }
}
