<?php

namespace App\Tests\Packages\Coach\Domain\Fixtures;

use App\Packages\Club\Domain\Entity\Club;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Coach\Domain\Entity\Value\CoachCity;
use App\Packages\Coach\Domain\Entity\Value\CoachCountry;
use App\Packages\Coach\Domain\Entity\Value\CoachEmail;
use App\Packages\Coach\Domain\Entity\Value\CoachName;
use App\Packages\Coach\Domain\Entity\Value\CoachSalary;
use App\Packages\Coach\Domain\Entity\Value\CoachUuid;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use DateTime;

class CoachFixtures
{
    const ID = 'a32568ba-96a7-af16-c2b4-f83fc1a48ab8';
    const NAME = 'CoachName';
    const SURNAME = 'CoachSurname';
    const BIRTH = '01-07-1990';
    const CITY = 'City';
    const COUNTRY = 'Country';
    const SALARY = 30000;
    const EMAIL = 'test@test.test';

    public static function createFreeCoach(): Coach
    {
        return new Coach(
            new CoachUuid(self::ID),
            new CoachName(self::NAME, self::SURNAME),
            new DateTime(self::BIRTH),
            new CoachCity(self::CITY),
            new CoachCountry(self::COUNTRY),
            null,
            new CoachEmail(self::EMAIL)
        );
    }

    public static function createToClub(): Coach
    {
        return new Coach(
            new CoachUuid(self::ID),
            new CoachName(self::NAME, self::SURNAME),
            new DateTime(self::BIRTH),
            new CoachCity(self::CITY),
            new CoachCountry(self::COUNTRY),
            null,
            new CoachEmail(self::EMAIL),
            ClubFixtures::create()
        );
    }
}