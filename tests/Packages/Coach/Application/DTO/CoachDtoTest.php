<?php

namespace App\Tests\Packages\Coach\Application\DTO;

use App\Packages\Coach\Application\DTO\CoachDto;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;

class CoachDtoTest extends TestCase
{
    public function testAssembleShouldReturnACoachDto()
    {
        $coach = CoachFixtures::createFreeCoach();
        $dto = CoachDto::assemble($coach);

        self::assertEquals($coach->getId()->value(), $dto->id);
        self::assertEquals($coach->getName()->name(), $dto->name);
        self::assertEquals($coach->getName()->surname(), $dto->surname);
        self::assertEquals($coach->getCity()->value(), $dto->city);
        self::assertEquals($coach->getCountry()->value(), $dto->country);
        self::assertEquals($coach->getSalary()?->value(), $dto->salary);
        self::assertEquals($coach->getEmail()->value(), $dto->email);
        self::assertEquals($coach->getClub()?->getName()->value(), $dto->club?->name);
    }
}
