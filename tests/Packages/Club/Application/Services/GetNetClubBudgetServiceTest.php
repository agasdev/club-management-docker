<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Services\GetNetClubBudgetService;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;

class GetNetClubBudgetServiceTest extends TestCase
{
    private GetNetClubBudgetService $service;
    private ClubRepository $clubRepository;

    public function setUp(): void
    {
        $this->clubRepository = $this->createMock(ClubRepository::class);

        $this->service = new GetNetClubBudgetService($this->clubRepository);
    }

    public function testInvokeShouldReturnIntWithoutSalary()
    {
        $salaryPlayers = 200000;
        $this->clubRepository
            ->expects(self::once())
            ->method('getTotalSalaryPlayers')
            ->willReturn($salaryPlayers);

        $salaryCoaches = 300000;
        $this->clubRepository
            ->expects(self::once())
            ->method('getTotalSalaryCoaches')
            ->willReturn($salaryCoaches);

        $result = ($this->service)(ClubFixtures::ID, ClubFixtures::BUDGET);

        self::assertEquals(
            ClubFixtures::BUDGET - ($salaryPlayers + $salaryCoaches),
            $result
        );
    }

    public function testInvokeShouldReturnIntWithSalary()
    {
        $salaryPlayers = 200000;
        $this->clubRepository
            ->expects(self::once())
            ->method('getTotalSalaryPlayers')
            ->willReturn($salaryPlayers);

        $salaryCoaches = 300000;
        $this->clubRepository
            ->expects(self::once())
            ->method('getTotalSalaryCoaches')
            ->willReturn($salaryCoaches);

        $result = ($this->service)(
            ClubFixtures::ID,
            ClubFixtures::BUDGET,
            PlayerFixtures::SALARY
        );

        self::assertEquals(
            ClubFixtures::BUDGET - ($salaryPlayers + $salaryCoaches + PlayerFixtures::SALARY),
            $result
        );
    }

    public function testInvokeShouldReturnNegativeInt()
    {
        $salaryPlayers = 200000;
        $this->clubRepository
            ->expects(self::once())
            ->method('getTotalSalaryPlayers')
            ->willReturn($salaryPlayers);

        $salaryCoaches = 3000000;
        $this->clubRepository
            ->expects(self::once())
            ->method('getTotalSalaryCoaches')
            ->willReturn($salaryCoaches);

        $result = ($this->service)(
            ClubFixtures::ID,
            ClubFixtures::BUDGET,
            PlayerFixtures::SALARY
        );

        self::assertEquals(
            ClubFixtures::BUDGET - ($salaryPlayers + $salaryCoaches + PlayerFixtures::SALARY),
            $result
        );
    }
}
