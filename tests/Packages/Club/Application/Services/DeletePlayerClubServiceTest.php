<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\PlayerNotFoundInClubException;
use App\Packages\Club\Application\Services\DeletePlayerClubService;
use App\Packages\Club\Application\Services\GetClubPlayerByIdService;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\SendEmail;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;

class DeletePlayerClubServiceTest extends TestCase
{
    private DeletePlayerClubService $service;
    private GetClubService $getClubService;
    private GetClubPlayerByIdService $getClubPlayerByIdService;
    private PlayerRepository $playerRepository;
    private SendEmail $sendEmail;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->getClubPlayerByIdService = $this->createMock(GetClubPlayerByIdService::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->sendEmail = $this->createMock(SendEmail::class);

        $this->service = new DeletePlayerClubService(
            $this->getClubService,
            $this->getClubPlayerByIdService,
            $this->playerRepository,
            $this->sendEmail
        );
    }

    public function testInvokeShouldReturnVoid()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $player = PlayerFixtures::createToClub();
        $this->getClubPlayerByIdService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($player);

        self::assertEquals($club, $player->getClub());

        $this->playerRepository
            ->expects(self::once())
            ->method('update');

        $this->sendEmail
            ->expects(self::once())
            ->method('__invoke');

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID);
    }

    public function testInvokeShouldThrowResourceNotFoundException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID);
    }

    public function testInvokeShouldReturnCoachNotFoundInClubException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getClubPlayerByIdService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new PlayerNotFoundInClubException());

        $this->expectException(PlayerNotFoundInClubException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID);
    }
}
