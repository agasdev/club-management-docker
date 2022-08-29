<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Player\Application\Services;

use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Packages\Common\Application\Services\SendEmail;
use App\Packages\Player\Application\DTO\PlayerDto;
use App\Packages\Player\Application\Services\CreatePlayerService;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreatePlayerServiceTest extends TestCase
{
    private CreatePlayerService $service;
    private CreateAndValidateForm $createAndValidateForm;
    private PlayerRepository $playerRepository;
    private ClubRepository $clubRepository;
    private SendEmail $sendEmail;
    private Request $request;

    public function setUp(): void
    {
        $this->createAndValidateForm = $this->createMock(CreateAndValidateForm::class);
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->clubRepository = $this->createMock(ClubRepository::class);
        $this->sendEmail = $this->createMock(SendEmail::class);
        $this->request = Request::create('some', 'POST');

        $this->service = new CreatePlayerService(
            $this->createAndValidateForm,
            $this->playerRepository,
            $this->clubRepository,
            $this->sendEmail
        );
    }

    public function testInvokeShouldReturnPlayerDto()
    {
        $playerDto = PlayerDto::assemble(PlayerFixtures::createFreePlayer());
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($playerDto);

        $this->playerRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->willReturn(null);

        $this->playerRepository
            ->expects(self::once())
            ->method('add');

        $result = ($this->service)($this->request);

        self::assertInstanceOf(PlayerDto::class, $result);
    }
}
