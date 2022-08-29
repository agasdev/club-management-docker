<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\PlayerNotFoundInClubException;
use App\Packages\Club\Application\Services\GetClubPlayerByIdService;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;

class GetClubPlayerByIdServiceTest extends TestCase
{
    private GetClubPlayerByIdService $service;
    private PlayerRepository $playerRepository;

    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);

        $this->service = new GetClubPlayerByIdService($this->playerRepository);
    }

    public function testInvokeShouldReturnPlayer()
    {
        $player = PlayerFixtures::createToClub();
        $this->playerRepository
            ->expects(self::once())
            ->method('findOneByClubIdAndId')
            ->willReturn($player);

        $result = ($this->service)(ClubFixtures::ID, PlayerFixtures::ID);

        self::assertEquals($player, $result);
    }

    public function testInvokeShouldThrowPlayerNotFoundInClubException()
    {
        $this->playerRepository
            ->expects(self::once())
            ->method('findOneByClubIdAndId')
            ->willReturn(null);

        $this->expectException(PlayerNotFoundInClubException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID);
    }
}
