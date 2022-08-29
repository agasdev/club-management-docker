<?php

namespace App\Tests\Packages\Player\Domain\Fixtures;

use App\Packages\Player\Domain\Entity\Player;
use App\Packages\Player\Domain\Entity\Value\PlayerCity;
use App\Packages\Player\Domain\Entity\Value\PlayerCountry;
use App\Packages\Player\Domain\Entity\Value\PlayerEmail;
use App\Packages\Player\Domain\Entity\Value\PlayerName;
use App\Packages\Player\Domain\Entity\Value\PlayerUuid;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use DateTime;

class PlayerFixtures
{
    const ID = 'a32568ba-96a7-af16-c2b4-f83fc1a67ab5';
    const NAME = 'PlayerName';
    const SURNAME = 'PlayerSurname';
    const BIRTH = '01-07-2001';
    const CITY = 'City';
    const COUNTRY = 'Country';
    const SALARY = 30000;
    const EMAIL = 'testplayer@test.test';

    public static function createFreePlayer(): Player
    {
        return new Player(
            new PlayerUuid(self::ID),
            new PlayerName(self::NAME, self::SURNAME),
            new DateTime(self::BIRTH),
            new PlayerCity(self::CITY),
            new PlayerCountry(self::COUNTRY),
            null,
            new PlayerEmail(self::EMAIL)
        );
    }

    public static function createToClub(): Player
    {
        return new Player(
            new PlayerUuid(self::ID),
            new PlayerName(self::NAME, self::SURNAME),
            new DateTime(self::BIRTH),
            new PlayerCity(self::CITY),
            new PlayerCountry(self::COUNTRY),
            null,
            new PlayerEmail(self::EMAIL),
            ClubFixtures::create()
        );
    }
}