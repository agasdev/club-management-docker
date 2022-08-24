<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity;

use App\Packages\Club\Infrastructure\Persistence\Doctrine\DoctrineClubRepository;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Player\Domain\Entity\Player;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DoctrineClubRepository::class)]
class Club
{
    private string $id;
    private string $name;
    private string $city;
    private string $country;
    private int $budget;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;
    private Collection $players;
    private Collection $coaches;

    public function __construct(
        string $id,
        string $name,
        string $city,
        string $country,
        int $budget,
        DateTimeImmutable $createdAt,
        ?DateTimeImmutable $updatedAt
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->budget = $budget;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->players = new ArrayCollection();
        $this->coaches = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getBudget(): int
    {
        return $this->budget;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setClub($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getClub() === $this) {
                $player->setClub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getCoaches(): Collection
    {
        return $this->coaches;
    }

    public function addCoach(Coach $coach): self
    {
        if (!$this->coaches->contains($coach)) {
            $this->coaches->add($coach);
            $coach->setClub($this);
        }

        return $this;
    }

    public function removeCoach(Coach $coach): self
    {
        if ($this->coaches->removeElement($coach)) {
            // set the owning side to null (unless already changed)
            if ($coach->getClub() === $this) {
                $coach->setClub(null);
            }
        }

        return $this;
    }
}
