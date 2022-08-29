<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Player\Application\DTO;

use App\Packages\Player\Application\DTO\PlayerDto;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;

class PlayerDtoTest extends TestCase
{
    public function testAssembleShouldReturnAPlayerDto()
    {
        $player = PlayerFixtures::createFreePlayer();
        $dto = PlayerDto::assemble($player);

        self::assertEquals($player->getId()->value(), $dto->id);
        self::assertEquals($player->getName()->name(), $dto->name);
        self::assertEquals($player->getName()->surname(), $dto->surname);
        self::assertEquals($player->getCity()->value(), $dto->city);
        self::assertEquals($player->getCountry()->value(), $dto->country);
        self::assertEquals($player->getSalary()?->value(), $dto->salary);
        self::assertEquals($player->getEmail()->value(), $dto->email);
        self::assertEquals($player->getClub()?->getName()->value(), $dto->club?->name);
    }
}
