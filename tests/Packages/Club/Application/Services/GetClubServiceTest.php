<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Club\Application\Services;

use App\Packages\Club\Application\Services\GetClubService;
use App\Packages\Club\Domain\Repository\ClubRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Tests\Packages\Club\Domain\Fixtures\ClubFixtures;
use PHPUnit\Framework\TestCase;

class GetClubServiceTest extends TestCase
{
    private GetClubService $service;
    private ClubRepository $clubRepository;

    public function setUp(): void
    {
        $this->clubRepository = $this->createMock(ClubRepository::class);

        $this->service = new GetClubService($this->clubRepository);
    }

    public function testInvokeShouldReturnClub()
    {
        $club = ClubFixtures::create();
        $this->clubRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn($club);

        $result = ($this->service)(ClubFixtures::ID);

        self::assertEquals($club, $result);
    }

    public function testInvokeShouldThrowResourceNotFoundException()
    {
        $this->clubRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(ClubFixtures::ID);
    }
}
