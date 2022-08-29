<?php

namespace App\Tests\Packages\Club\Domain\Fixtures;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Club\Domain\Entity\Value\ClubBudget;
use App\Packages\Club\Domain\Entity\Value\ClubCity;
use App\Packages\Club\Domain\Entity\Value\ClubCountry;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;

class ClubFixtures
{
    const ID = 'a32568ba-96a7-af16-c2b4-f83fc1a48dc9';
    const NAME = 'ClubName';
    const CITY = 'City';
    const COUNTRY = 'Country';
    const BUDGET = 3000000;

    public static function create(): Club
    {
        return new Club(
            new ClubUuid(self::ID),
            new ClubName(self::NAME),
            new ClubCity(self::CITY),
            new ClubCountry(self::COUNTRY),
            new ClubBudget(self::BUDGET)
        );
    }
}