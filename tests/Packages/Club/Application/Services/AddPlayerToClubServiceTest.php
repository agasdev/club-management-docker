<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Club\Application\Services\AddPlayerToClubService;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Club\Application\Services\GetNetClubBudgetService;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Packages\Common\Application\Services\SendEmail;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Services\GetPlayerService;
use App\Packages\Player\Domain\Exception\InvalidPlayerNameException;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AddPlayerToClubServiceTest extends TestCase
{
    private AddPlayerToClubService $service;
    private GetClubService $getClubService;
    private GetPlayerService $getPlayerService;
    private GetNetClubBudgetService $getNetClubBudgetService;
    private CreateAndValidateForm $createAndValidateForm;
    private PlayerRepository $playerRepository;
    private SendEmail $sendEmail;
    private Request $request;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->getPlayerService = $this->createMock(GetPlayerService::class);
        $this->getNetClubBudgetService = $this->createMock(GetNetClubBudgetService::class);
        $this->createAndValidateForm = $this->createMock(CreateAndValidateForm::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->sendEmail = $this->createMock(SendEmail::class);
        $this->request = Request::create('some', 'POST', ['salary' => 10000]);

        $this->service = new AddPlayerToClubService(
            $this->getClubService,
            $this->getPlayerService,
            $this->getNetClubBudgetService,
            $this->createAndValidateForm,
            $this->playerRepository,
            $this->sendEmail
        );
    }

    public function testInvokeShouldReturnPlayerDto()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $player = PlayerFixtures::createFreePlayer();
        $this->getPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($player);

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(2500000);

        $playerDto = PlayerDto::assemble($player);
        $playerDto->salary = 10000;
        $playerDto->club = ClubDto::assemble($club);
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($playerDto);

        $this->playerRepository
            ->expects(self::once())
            ->method('update');

        $this->sendEmail
            ->expects(self::once())
            ->method('__invoke');

        $result = ($this->service)(ClubFixtures::ID, PlayerFixtures::ID, $this->request);

        self::assertEquals($playerDto, $result);
    }

    public function testInvokeShouldThrowResourceNotFoundExceptionByClub()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowResourceNotFoundExceptionByPlayer()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowRequiredSalaryFieldException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(PlayerFixtures::createFreePlayer());

        $this->expectException(RequiredSalaryFieldException::class);

        ($this->service)(
            ClubFixtures::ID,
            PlayerFixtures::ID,
            Request::create('some', 'POST', ['salary' => ''])
        );
    }

    public function testInvokeShouldThrowInsufficientBudgetException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(PlayerFixtures::createFreePlayer());

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(-2000);

        $this->expectException(InsufficientBudgetException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowInvalidResourceExceptionByForm()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(PlayerFixtures::createFreePlayer());

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(200000);

        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowInvalidPlayerNameException()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $player = PlayerFixtures::createFreePlayer();
        $this->getPlayerService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($player);

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(200000);

        $playerDto = PlayerDto::assemble($player);
        $playerDto->salary = 10000;
        $playerDto->club = ClubDto::assemble($club);
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($playerDto);

        $this->playerRepository
            ->expects(self::once())
            ->method('update')
            ->willThrowException(new InvalidPlayerNameException());

        $this->expectException(InvalidResourceException::class);

        ($this->service)(ClubFixtures::ID, PlayerFixtures::ID, $this->request);
    }

}
