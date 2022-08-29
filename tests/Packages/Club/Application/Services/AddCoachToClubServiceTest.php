<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Exception\InsufficientBudgetException;
use App\Packages\Club\Application\Exception\RequiredSalaryFieldException;
use App\Packages\Club\Application\Services\AddCoachToClubService;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Club\Application\Services\GetNetClubBudgetService;
use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Services\GetCoachService;
use App\Packages\Coach\Domain\Exception\InvalidCoachNameException;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Packages\Common\Application\Services\SendEmail;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AddCoachToClubServiceTest extends TestCase
{
    private AddCoachToClubService $service;
    private GetClubService $getClubService;
    private GetCoachService $getCoachService;
    private GetNetClubBudgetService $getNetClubBudgetService;
    private CreateAndValidateForm $createAndValidateForm;
    private CoachRepository $coachRepository;
    private SendEmail $sendEmail;
    private Request $request;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->getCoachService = $this->createMock(GetCoachService::class);
        $this->getNetClubBudgetService = $this->createMock(GetNetClubBudgetService::class);
        $this->createAndValidateForm = $this->createMock(CreateAndValidateForm::class);
        $this->coachRepository = $this->createMock(CoachRepository::class);
        $this->sendEmail = $this->createMock(SendEmail::class);
        $this->request = Request::create('some', 'POST', ['salary' => 10000]);

        $this->service = new AddCoachToClubService(
            $this->getClubService,
            $this->getCoachService,
            $this->getNetClubBudgetService,
            $this->createAndValidateForm,
            $this->coachRepository,
            $this->sendEmail
        );
    }

    public function testInvokeShouldReturnCoachDto()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $coach = CoachFixtures::createFreeCoach();
        $this->getCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($coach);

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(2500000);

        $coachDto = CoachDto::assemble($coach);
        $coachDto->salary = 10000;
        $coachDto->club = ClubDto::assemble($club);
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($coachDto);

        $this->coachRepository
            ->expects(self::once())
            ->method('update');

        $this->sendEmail
            ->expects(self::once())
            ->method('__invoke');

        $result = ($this->service)(ClubFixtures::ID, CoachFixtures::ID, $this->request);

        self::assertEquals($coachDto, $result);
    }

    public function testInvokeShouldThrowResourceNotFoundExceptionByClub()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowResourceNotFoundExceptionByCoach()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowRequiredSalaryFieldException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(CoachFixtures::createFreeCoach());

        $this->expectException(RequiredSalaryFieldException::class);

        ($this->service)(
            ClubFixtures::ID,
            CoachFixtures::ID,
            Request::create('some', 'POST', ['salary' => ''])
        );
    }

    public function testInvokeShouldThrowInsufficientBudgetException()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(CoachFixtures::createFreeCoach());

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(-2000);

        $this->expectException(InsufficientBudgetException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowInvalidResourceExceptionByForm()
    {
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubFixtures::create());

        $this->getCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(CoachFixtures::createFreeCoach());

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(200000);

        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new ResourceNotFoundException());

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID, $this->request);
    }

    public function testInvokeShouldThrowInvalidCoachNameException()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $coach = CoachFixtures::createFreeCoach();
        $this->getCoachService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($coach);

        $this->getNetClubBudgetService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(200000);

        $coachDto = CoachDto::assemble($coach);
        $coachDto->salary = 10000;
        $coachDto->club = ClubDto::assemble($club);
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($coachDto);

        $this->coachRepository
            ->expects(self::once())
            ->method('update')
            ->willThrowException(new InvalidCoachNameException());

        $this->expectException(InvalidResourceException::class);

        ($this->service)(ClubFixtures::ID, CoachFixtures::ID, $this->request);
    }
}
