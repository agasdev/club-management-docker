<?php

declare(strict_types=1);

namespace App\Packages\Club\Domain\Entity;

use App\Packages\Club\Domain\Entity\Value\ClubBudget;
use App\Packages\Club\Domain\Entity\Value\ClubCity;
use App\Packages\Club\Domain\Entity\Value\ClubCountry;
use App\Packages\Club\Domain\Entity\Value\ClubName;
use App\Packages\Club\Domain\Entity\Value\ClubUuid;
use App\Packages\Club\Infrastructure\Persistence\Doctrine\DoctrineClubRepository;
use App\Packages\Coach\Domain\Entity\Coach;
use App\Packages\Player\Domain\Entity\Player;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;

#[ORM\Entity(repositoryClass: DoctrineClubRepository::class)]
class Club
{
    private ClubUuid $id;
    private ClubName $name;
    private ClubCity $city;
    private ClubCountry $country;
    private ClubBudget $budget;
    /**
     * @Type("DateTimeImmutable<'Y-m-d H:i:s'>")
     */
    private DateTimeImmutable $createdAt;
    /**
     * @Type("DateTimeImmutable<'Y-m-d H:i:s'>")
     */
    private ?DateTimeImmutable $updatedAt;
    private Collection $players;
    private Collection $coaches;

    public function __construct(
        ClubUuid $id,
        ClubName $name,
        ClubCity $city,
        ClubCountry $country,
        ClubBudget $budget
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->budget = $budget;
        $this->players = new ArrayCollection();
        $this->coaches = new ArrayCollection();
    }

    public function getId(): ClubUuid
    {
        return $this->id;
    }

    public function getName(): ClubName
    {
        return $this->name;
    }

    public function getCity(): ClubCity
    {
        return $this->city;
    }

    public function getCountry(): ClubCountry
    {
        return $this->country;
    }

    public function getBudget(): ClubBudget
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

    public function onPrePersist()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function onPreUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function update(
        ClubName $name,
        ClubCity $city,
        ClubCountry $country,
        ClubBudget $budget
    ): void
    {
        $this->name = $name;
        $this->city = $city;
        $this->country = $country;
        $this->budget = $budget;
    }
}
