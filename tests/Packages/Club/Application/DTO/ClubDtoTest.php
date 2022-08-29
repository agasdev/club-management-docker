<?php

namespace App\Tests\Packages\Club\Application\DTO;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use PHPUnit\Framework\TestCase;

class ClubDtoTest extends TestCase
{
    public function testAssembleShouldReturnAClubDto()
    {
        $club = ClubFixtures::create();
        $dto = ClubDto::assemble($club);

        self::assertEquals($club->getId()->value(), $dto->id);
        self::assertEquals($club->getName()->value(), $dto->name);
        self::assertEquals($club->getCity()->value(), $dto->city);
        self::assertEquals($club->getCountry()->value(), $dto->country);
        self::assertEquals($club->getBudget()->value(), $dto->budget);
    }
}
