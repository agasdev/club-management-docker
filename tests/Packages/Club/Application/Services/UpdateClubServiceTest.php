<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Club\Application\Services\GetNetClubBudgetService;
use App\Packages\Club\Application\Services\UpdateClubService;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UpdateClubServiceTest extends TestCase
{
    private UpdateClubService $service;
    private GetClubService $getClubService;
    private CreateAndValidateForm $createAndValidateForm;
    private GetNetClubBudgetService $getNetClubBudgetService;
    private ClubRepository $clubRepository;
    private Request $request;

    public function setUp(): void
    {
        $this->getClubService = $this->createMock(GetClubService::class);
        $this->createAndValidateForm = $this->createMock(CreateAndValidateForm::class);
        $this->getNetClubBudgetService = $this->createMock(GetNetClubBudgetService::class);
        $this->clubRepository = $this->createMock(ClubRepository::class);
        $this->request = Request::create('some', 'PATCH');

        $this->service = new UpdateClubService(
            $this->getClubService,
            $this->createAndValidateForm,
            $this->getNetClubBudgetService,
            $this->clubRepository
        );
    }

    public function testInvokeShouldReturnClubDto()
    {
        $club = ClubFixtures::create();
        $this->getClubService
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($club);

        $clubDto = ClubDto::assemble($club);
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($clubDto);

        $this->clubRepository
            ->expects(self::once())
            ->method('update');

        $result = ($this->service)(ClubFixtures::ID, $this->request);

        self::assertEquals($clubDto, $result);
    }
}
