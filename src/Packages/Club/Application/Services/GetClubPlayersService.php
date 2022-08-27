<?php

declare(strict_types=1);

namespace App\Packages\Club\Application\Services;

use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Common\Application\DTO\PaginationDto;
use App\Packages\Common\Application\Exception\ResourceNotFoundException;
use App\Packages\Player\Application\DTO\PlayerCollectionDto;
use App\Packages\Player\Domain\Repository\PlayerRepository;
use Symfony\Component\HttpFoundation\Request;

class GetClubPlayersService
{
    private const MAX_ITEMS_PER_PAGE = 100;
    private const MAX_ITEMS_PER_PAGE_WITH_SEARCH = 25;

    public function __construct(
        private GetClubService $getClubService,
        private PlayerRepository $playerRepository
    )
    {
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function __invoke(string $id, Request $request): PlayerCollectionDto
    {
        ($this->getClubService)($id);

        if (empty($search = $request->get('search'))) {
            $search = "";
        }
        $page = $request->get('page', 1);
        $page = empty($page) ? 1 : (int)$page;
        $itemsPerPage = (int)$request->get('itemsPerPage');
        $itemsPerPage = empty($search)
            ? $this->getItemsPerPage($itemsPerPage, self::MAX_ITEMS_PER_PAGE)
            : $this->getItemsPerPage($itemsPerPage, self::MAX_ITEMS_PER_PAGE_WITH_SEARCH);

        [$playersDto, $totalMatchedAttendees] = $this->playerRepository->findByClubId(
            new ClubUuid($id),
            $page,
            $itemsPerPage,
            $search
        );

        return new PlayerCollectionDto(
            $playersDto,
            new PaginationDto(
                $totalMatchedAttendees,
                PaginationDto::calculateNumberOfPages($totalMatchedAttendees, $itemsPerPage),
                $page
            )
        );
    }

    private function getItemsPerPage(int $itemsRequested, int $maxItems): int
    {
        return  min(
            0 === $itemsRequested ? $maxItems : $itemsRequested,
            $maxItems);
    }
}