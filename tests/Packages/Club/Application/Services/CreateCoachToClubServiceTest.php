<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Club\Application\Services\CreateCoachToClubService;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Club\Application\Services\GetNetClubBudgetService;
use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Services\CreateCoachService;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateCoachToClubServiceTest extends TestCase
{
    private CreateCoachToClubService $service;
    private GetClubService $getClubService;
    private GetNetClubBudgetService $getNetClubBudgetService;
    private CreateCoachService $createCoachService;
    private Request $request;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->getNetClubBudgetService = $this->createMock(GetNetClubBudgetService::class);
        $this->createCoachService = $this->createMock(CreateCoachService::class);
        $this->request = Request::create('some', 'POST', ['salary' => 10000]);

        $this->service = new CreateCoachToClubService(
            $this->getClubService,
            $this->getNetClubBudgetService,
            $this->createCoachService
        );
    }

    public function testInvokeShouldReturnCoachDto()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(2400000);

        $this->createCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(CoachDto::assemble(CoachFixtures::createToClub()));

        $result = ($this->service)(ClubFixtures::ID, $this->request);

        self::assertInstanceOf(CoachDto::class, $result);
    }

    public function testInvokeShouldThrowResourceNotFoundException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowRequiredSalaryFieldException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->expectException(RequiredSalaryFieldException::class);

        ($this->service)(
            ClubFixtures::ID,
            Request::create('some', 'POST', ['salary' => ''])
        );
    }

    public function testInvokeShouldThrowInsufficientBudgetException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(-2000);

        $this->expectException(InsufficientBudgetException::class);

        ($this->service)(ClubFixtures::ID, $this->request);
    }
}
