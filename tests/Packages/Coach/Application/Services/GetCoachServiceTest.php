<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Coach\Application\Services;

use App\Packages\Coach\Application\Services\GetCoachService;
use App\Packages\Coach\Domain\Repository\CoachRepository;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Tests\Packages\Coach\Domain\Fixtures\CoachFixtures;
use PHPUnit\Framework\TestCase;

class GetCoachServiceTest extends TestCase
{
    private GetCoachService $service;
    private CoachRepository $coachRepository;

    public function setUp(): void
    {
        $this->coachRepository = $this->createMock(CoachRepository::class);

        $this->service = new GetCoachService($this->coachRepository);
    }

    public function testInvokeShouldReturnCoach()
    {
        $coach = CoachFixtures::createFreeCoach();
        $this->coachRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn($coach);

        $result = ($this->service)(CoachFixtures::ID);

        self::assertEquals($coach, $result);
    }

    public function testInvokeShouldThrowResourceNotFoundException()
    {
        $this->coachRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(CoachFixtures::ID);
    }
}
