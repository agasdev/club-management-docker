<?php

namespace App\Packages\Player\Application\DTO;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Player\Domain\Entity\Player;

class PlayerDto
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

    public static function assemble(Player $player): self
    {
        return new self(
            $player->getId()->value(),
            $player->getName()->name(),
            $player->getName()->surname(),
            $player->getDateOfBirth()->format('Y-m-d'),
            $player->getCity()->value(),
            $player->getCountry()->value(),
            $player->getSalary()?->value(),
            $player->getEmail()->value(),
            $player->getClub() ? ClubDto::assemble($player->getClub()) : null
        );
    }


    public static function createEmpty(): self
    {
        return new self();
    }
}