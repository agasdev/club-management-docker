<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Coach\Application\Services;

use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Coach\Application\DTO\CoachDto;
use App\Packages\Coach\Application\Services\CreateCoachService;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Packages\Common\Application\Services\SendEmail;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateCoachServiceTest extends TestCase
{
    private CreateCoachService $service;
    private CreateAndValidateForm $createAndValidateForm;
    private CoachRepository $coachRepository;
    private ClubRepository $clubRepository;
    private SendEmail $sendEmail;
    private Request $request;

    public function setUp(): void
    {
        $this->createAndValidateForm = $this->createMock(CreateAndValidateForm::class);
        $this->coachRepository = $this->createMock(CoachRepository::class);
        $this->clubRepository = $this->createMock(ClubRepository::class);
        $this->sendEmail = $this->createMock(SendEmail::class);
        $this->request = Request::create('some', 'POST');

        $this->service = new CreateCoachService(
            $this->createAndValidateForm,
            $this->coachRepository,
            $this->clubRepository,
            $this->sendEmail
        );
    }

    public function testInvokeShouldReturnCoachDto()
    {
        $coachDto = CoachDto::assemble(CoachFixtures::createFreeCoach());
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($coachDto);

        $this->coachRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->willReturn(null);

        $this->coachRepository
            ->expects(self::once())
            ->method('add');

        $result = ($this->service)($this->request);

        self::assertInstanceOf(CoachDto::class, $result);
    }
}
