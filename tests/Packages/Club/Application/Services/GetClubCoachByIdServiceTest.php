<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\CoachNotFoundInClubException;
use App\Packages\Club\Application\Services\GetClubCoachByIdService;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;

class GetClubCoachByIdServiceTest extends TestCase
{
    private GetClubCoachByIdService $service;
    private CoachRepository $coachRepository;

    public function setUp(): void
    {
        $this->coachRepository = $this->createMock(CoachRepository::class);

        $this->service = new GetClubCoachByIdService($this->coachRepository);
    }

    public function testInvokeShouldReturnCoach()
    {
        $coach = CoachFixtures::createToClub();
        $this->coachRepository
            ->expects(self::once())
            ->method('findOneByClubIdAndId')
            ->willReturn($coach);

        $result = ($this->service)(ClubFixtures::ID, CoachFixtures::ID);

        self::assertEquals($coach, $result);
    }

    public function testInvokeShouldThrowCoachNotFoundInClubException()
    {
        $this->coachRepository
            ->expects(self::once())
            ->method('findOneByClubIdAndId')
            ->willReturn(null);

        $this->expectException(CoachNotFoundInClubException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID);
    }
}
