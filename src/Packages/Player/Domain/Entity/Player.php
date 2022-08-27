<?php

declare(strict_types=1);

namespace App\Packages\Player\Domain\Entity;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Player\Domain\Entity\Value\PlayerCity;
use App\Packages\Player\Domain\Entity\Value\PlayerCountry;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Entity\Value\PlayerName;
use App\Packages\Player\Domain\Entity\Value\PlayerSalary;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;
use DateTime;
use DateTimeImmutable;

class Player
{
    private PlayerUuid $id;
    private PlayerName $name;
    private DateTime $dateOfBirth;
    private PlayerCity $city;
    private PlayerCountry $country;
    private ?PlayerSalary $salary;
    private PlayerEmail $email;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private ?Club $club;

    public function __construct(
        PlayerUuid $id,
        PlayerName $name,
        DateTime $dateOfBirth,
        PlayerCity $city,
        PlayerCountry $country,
        ?PlayerSalary $salary,
        PlayerEmail $email,
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


    public function getId(): PlayerUuid
    {
        return $this->id;
    }

    public function getName(): PlayerName
    {
        return $this->name;
    }

    public function getCity(): PlayerCity
    {
        return $this->city;
    }

    public function getCountry(): PlayerCountry
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

    public function getSalary(): ?PlayerSalary
    {
        return $this->salary;
    }

    public function getEmail(): PlayerEmail
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
}
