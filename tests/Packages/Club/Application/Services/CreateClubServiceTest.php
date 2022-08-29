<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\DTO\ClubDto;
use App\Packages\Club\Application\Exception\ClubAlreadyExistException;
use App\Packages\Club\Application\Exception\InvalidClubFormException;
use App\Packages\Club\Application\Services\CreateClubService;
use App\Packages\Club\Domain\Exception\InvalidClubNameException;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Exception\InvalidResourceException;
use App\Packages\Common\Application\Services\CreateAndValidateForm;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateClubServiceTest extends TestCase
{
    private CreateClubService $service;
    private CreateAndValidateForm $createAndValidateForm;
    private ClubRepository $clubRepository;
    private Request $request;

    public function setUp(): void
    {
        $this->createAndValidateForm = $this->createMock(CreateAndValidateForm::class);
        $this->clubRepository = $this->createMock(ClubRepository::class);
        $this->request = Request::create('some', 'POST');

        $this->service = new CreateClubService(
            $this->createAndValidateForm,
            $this->clubRepository
        );
    }

    public function testInvokeShouldReturnClubDto()
    {
        $club = ClubFixtures::create();
        $clubDto = ClubDto::assemble($club);
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn($clubDto);

        $this->clubRepository
            ->expects(self::once())
            ->method('findOneByName')
            ->willReturn(null);

        $this->clubRepository
            ->expects(self::once())
            ->method('add');

        $result = ($this->service)($this->request);

        self::assertInstanceOf(ClubDto::class, $result);
    }

    public function testInvokeShouldThrowInvalidClubFormException()
    {
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willThrowException(new InvalidResourceException());

        $this->expectException(InvalidClubFormException::class);

        ($this->service)($this->request);
    }

    public function testInvokeShouldThrowClubAlreadyExistException()
    {
        $club = ClubFixtures::create();
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubDto::assemble($club));

        $this->clubRepository
            ->expects(self::once())
            ->method('findOneByName')
            ->willReturn($club);

        $this->expectException(ClubAlreadyExistException::class);

        ($this->service)($this->request);
    }

    public function testInvokeShouldThrowInvalidResourceException()
    {
        $club = ClubFixtures::create();
        $this->createAndValidateForm
            ->expects(self::once())
            ->method('__invoke')
            ->willReturn(ClubDto::assemble($club));

        $this->clubRepository
            ->expects(self::once())
            ->method('findOneByName')
            ->willReturn(null);

        $this->clubRepository
            ->expects(self::once())
            ->method('add')
            ->willThrowException(new InvalidClubNameException());

        $this->expectException(InvalidResourceException::class);

        ($this->service)($this->request);
    }

}
