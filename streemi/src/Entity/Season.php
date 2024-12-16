<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $seasonNumber = null;

    #[ORM\ManyToOne(inversedBy: 'seasonList')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Serie $serieId = null;

    /**
     * @var Collection<int, Episode>
     */
    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'seasonId')]
    private Collection $episodeList;

    public function __construct()
    {
        $this->episodeList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeasonNumber(): ?int
    {
        return $this->seasonNumber;
    }

    public function setSeasonNumber(int $seasonNumber): static
    {
        $this->seasonNumber = $seasonNumber;

        return $this;
    }

    public function getSerieId(): ?Serie
    {
        return $this->serieId;
    }

    public function setSerieId(?Serie $serieId): static
    {
        $this->serieId = $serieId;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodeList(): Collection
    {
        return $this->episodeList;
    }

    public function addEpisodeList(Episode $episodeList): static
    {
        if (!$this->episodeList->contains($episodeList)) {
            $this->episodeList->add($episodeList);
            $episodeList->setSeasonId($this);
        }

        return $this;
    }

    public function removeEpisodeList(Episode $episodeList): static
    {
        if ($this->episodeList->removeElement($episodeList)) {
            if ($episodeList->getSeasonId() === $this) {
                $episodeList->setSeasonId(null);
            }
        }

        return $this;
    }
}
