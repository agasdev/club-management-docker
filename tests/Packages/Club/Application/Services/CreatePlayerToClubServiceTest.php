<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Club\Application\Services\CreatePlayerToClubService;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Club\Application\Services\GetNetClubBudgetService;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Services\CreatePlayerService;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreatePlayerToClubServiceTest extends TestCase
{
    private CreatePlayerToClubService $service;
    private GetClubService $getClubService;
    private GetNetClubBudgetService $getNetClubBudgetService;
    private CreatePlayerService $createPlayerService;
    private Request $request;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->getNetClubBudgetService = $this->createMock(GetNetClubBudgetService::class);
        $this->createPlayerService = $this->createMock(CreatePlayerService::class);
        $this->request = Request::create('some', 'POST', ['salary' => 10000]);

        $this->service = new CreatePlayerToClubService(
            $this->getClubService,
            $this->getNetClubBudgetService,
            $this->createPlayerService
        );
    }

    public function testInvokeShouldReturnPlayerDto()
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

        $this->createPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(PlayerDto::assemble(PlayerFixtures::createToClub()));

        $result = ($this->service)(ClubFixtures::ID, $this->request);

        self::assertInstanceOf(PlayerDto::class, $result);
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
