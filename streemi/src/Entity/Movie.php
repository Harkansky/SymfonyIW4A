<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie extends Media
{
    // Relation ManyToMany avec l'entité Language
    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: 'mediaList')]
    #[ORM\JoinTable(name: 'movie_languages')]
    private Collection $languages;

    // Constructeur pour initialiser la collection
    public function __construct()
    {
        $this->languages = new ArrayCollection();
    }

    // Méthode pour obtenir les langues du film
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    // Méthode pour ajouter une langue
    public function addLanguage(Language $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
            $language->addMediaList($this); // Ajoute ce film à la liste des médias du langage
        }

        return $this;
    }

    // Méthode pour retirer une langue
    public function removeLanguage(Language $language): self
    {
        if ($this->languages->removeElement($language)) {
            $language->removeMediaList($this); // Retire ce film de la liste des médias du langage
        }

        return $this;
    }
}

