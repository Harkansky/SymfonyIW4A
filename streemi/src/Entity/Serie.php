<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Serie extends Media
{
    /**
     * @var Collection<int, Season>
     */
    #[ORM\OneToMany(targetEntity: Season::class, mappedBy: 'serieId')]
    private Collection $seasonList;

    public function __construct()
    {
        $this->seasonList = new ArrayCollection();
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasonList(): Collection
    {
        return $this->seasonList;
    }

    public function addSeasonList(Season $seasonList): static
    {
        if (!$this->seasonList->contains($seasonList)) {
            $this->seasonList->add($seasonList);
            $seasonList->setSerieId($this);
        }

        return $this;
    }

    public function removeSeasonList(Season $seasonList): static
    {
        if ($this->seasonList->removeElement($seasonList)) {
            if ($seasonList->getSerieId() === $this) {
                $seasonList->setSerieId(null);
            }
        }

        return $this;
    }
}
