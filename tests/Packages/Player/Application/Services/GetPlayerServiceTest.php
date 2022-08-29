<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace App\Tests\Packages\Player\Application\Services;

use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Player\Application\Services\GetPlayerService;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use App\Tests\Packages\Player\Domain\Fixtures\PlayerFixtures;
use PHPUnit\Framework\TestCase;

class GetPlayerServiceTest extends TestCase
{
    private GetPlayerService $service;
    private PlayerRepository $playerRepository;

    public function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);

        $this->service = new GetPlayerService($this->playerRepository);
    }

    public function testInvokeShouldReturnPlayer()
    {
        $player = PlayerFixtures::createFreePlayer();
        $this->playerRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn($player);

        $result = ($this->service)(PlayerFixtures::ID);

        self::assertEquals($player, $result);
    }

    public function testInvokeShouldThrowResourceNotFoundException()
    {
        $this->playerRepository
            ->expects(self::once())
            ->method('find')
            ->willReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        ($this->service)(PlayerFixtures::ID);
    }
}
