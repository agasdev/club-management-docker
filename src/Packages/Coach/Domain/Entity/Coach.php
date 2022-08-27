<?php

declare(strict_types=1);

namespace App\Packages\Coach\Domain\Entity;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Coach\Domain\Entity\Value\CoachCity;
use App\Packages\Coach\Domain\Entity\Value\CoachCountry;
use App\Packages\Coach\Domain\Entity\Value\CoachEmail;
use App\Packages\Coach\Domain\Entity\Value\CoachName;
use App\Packages\Coach\Domain\Entity\Value\CoachSalary;
use App\Packages\Coach\Domain\Entity\Value\CoachUuid;
use App\Packages\Coach\Infrastructure\Persistence\Doctrine\DoctrineCoachRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineCoachRepository::class)]
class Coach
{
    private CoachUuid $id;
    private CoachName $name;
    private DateTime $dateOfBirth;
    private CoachCity $city;
    private CoachCountry $country;
    private ?CoachSalary $salary;
    private CoachEmail $email;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private ?Club $club;

    public function __construct(
        CoachUuid $id,
        CoachName $name,
        DateTime $dateOfBirth,
        CoachCity $city,
        CoachCountry $country,
        ?CoachSalary $salary,
        CoachEmail $email,
        ?Club $club = null,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->city = $city;
        $this->country = $country;
        $this->salary = $salary;
        $this->email = $email;
        $this->club = $club;
    }


    public function getId(): CoachUuid
    {
        return $this->id;
    }

    public function getName(): CoachName
    {
        return $this->name;
    }

    public function getCity(): CoachCity
    {
        return $this->city;
    }

    public function getCountry(): CoachCountry
    {
        return $this->country;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
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

    public function getSalary(): ?CoachSalary
    {
        return $this->salary;
    }

    public function getEmail(): CoachEmail
    {
        return $this->email;
    }

    public function onPrePersist()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function onPreUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function update(
        CoachName $name,
        DateTime $dateOfBirth,
        CoachCity $city,
        CoachCountry $country,
        ?CoachSalary $salary,
        CoachEmail $email,
        ?Club $club = null
    ): void
    {
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
        $this->city = $city;
        $this->country = $country;
        $this->salary = $salary;
        $this->email = $email;
        $this->club = $club;
    }
}
