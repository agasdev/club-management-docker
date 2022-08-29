<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\CoachNotFoundInClubException;
use App\Packages\Club\Application\Services\DeleteCoachClubService;
use App\Packages\Club\Application\Services\GetClubCoachByIdService;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\SendEmail;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;

class DeleteCoachClubServiceTest extends TestCase
{
    private DeleteCoachClubService $service;
    private GetClubService $getClubService;
    private GetClubCoachByIdService $getClubCoachByIdService;
    private CoachRepository $coachRepository;
    private SendEmail $sendEmail;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->getClubCoachByIdService = $this->createMock(GetClubCoachByIdService::class);
        $this->coachRepository = $this->createMock(CoachRepository::class);
        $this->sendEmail = $this->createMock(SendEmail::class);

        $this->service = new DeleteCoachClubService(
            $this->getClubService,
            $this->getClubCoachByIdService,
            $this->coachRepository,
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

        $coach = CoachFixtures::createToClub();
        $this->getClubCoachByIdService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($coach);

        self::assertEquals($club, $coach->getClub());

        $this->coachRepository
            ->expects(self::once())
            ->method('update');

        $this->sendEmail
            ->expects(self::once())
            ->method('__invoke');

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID);
    }

    public function testInvokeShouldThrowResourceNotFoundException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID);
    }

    public function testInvokeShouldReturnCoachNotFoundInClubException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getClubCoachByIdService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new CoachNotFoundInClubException());

        $this->expectException(CoachNotFoundInClubException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID);
    }
}
