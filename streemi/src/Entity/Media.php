<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap(['serie' => Serie::class, 'movie' => Movie::class])]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $mediaType = null;


    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $longDescription = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(length: 255)]
    private ?string $coverImage = null;

    #[ORM\Column]
    private array $staff = [];

    #[ORM\Column]
    private array $casting = [];

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'mediaId')]
    private Collection $commentList;

    /**
     * @var Collection<int, PlaylistMedia>
     */
    #[ORM\ManyToMany(targetEntity: PlaylistMedia::class, mappedBy: 'mediaId')]
    private Collection $mediaPlaylist;

    /**
     * @var Collection<int, WatchHistory>
     */
    #[ORM\OneToMany(mappedBy: 'mediaId', targetEntity: WatchHistory::class)]
    private Collection $watchHistories;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'mediaList')]
    private Collection $categorieMedia;

    /**
     * @var Collection<int, Language>
     */
    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: 'mediaList')]
    private Collection $mediaLanguage;

    public function __construct()
    {
        $this->commentList = new ArrayCollection();
        $this->mediaPlaylist = new ArrayCollection();
        $this->watchHistories = new ArrayCollection();
        $this->categorieMedia = new ArrayCollection();
        $this->mediaLanguage = new ArrayCollection();
    }

    // Getters and setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): static
    {
        $this->mediaType = $mediaType;

        return $this;
    }


    public function setMediaLanguage(Collection $mediaLanguage): static
    {
        $this->mediaLanguage = $mediaLanguage;

        return $this;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getStaff(): array
    {
        return $this->staff;
    }

    public function setStaff(array $staff): static
    {
        $this->staff = $staff;

        return $this;
    }

    public function getCasting(): array
    {
        return $this->casting;
    }

    public function setCasting(array $casting): static
    {
        $this->casting = $casting;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentList(): Collection
    {
        return $this->commentList;
    }

    public function addCommentList(Comment $commentList): static
    {
        if (!$this->commentList->contains($commentList)) {
            $this->commentList->add($commentList);
            $commentList->setMedia($this);
        }

        return $this;
    }

    public function removeCommentList(Comment $commentList): static
    {
        if ($this->commentList->removeElement($commentList)) {
            if ($commentList->getMedia() === $this) {
                $commentList->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlaylistMedia>
     */
    public function getMediaPlaylist(): Collection
    {
        return $this->mediaPlaylist;
    }

    public function addMediaPlaylist(PlaylistMedia $mediaPlaylist): static
    {
        if (!$this->mediaPlaylist->contains($mediaPlaylist)) {
            $this->mediaPlaylist->add($mediaPlaylist);
            $mediaPlaylist->setMedia($this);
        }

        return $this;
    }

    public function removeMediaPlaylist(PlaylistMedia $mediaPlaylist): static
    {
        if ($this->mediaPlaylist->removeElement($mediaPlaylist)) {
            $mediaPlaylist->setMedia(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, WatchHistory>
     */
    public function getWatchHistories(): Collection
    {
        return $this->watchHistories;
    }

    public function addWatchHistory(WatchHistory $watchHistory): static
    {
        if (!$this->watchHistories->contains($watchHistory)) {
            $this->watchHistories->add($watchHistory);
            $watchHistory->setMediaId($this);
        }

        return $this;
    }

    public function removeWatchHistory(WatchHistory $watchHistory): static
    {
        if ($this->watchHistories->removeElement($watchHistory)) {
            if ($watchHistory->getMediaId() === $this) {
                $watchHistory->setMediaId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategorieMedia(): Collection
    {
        return $this->categorieMedia;
    }

    public function addCategorieMedium(Categorie $categorieMedium): static
    {
        if (!$this->categorieMedia->contains($categorieMedium)) {
            $this->categorieMedia->add($categorieMedium);
        }

        return $this;
    }

    public function removeCategorieMedium(Categorie $categorieMedium): static
    {
        $this->categorieMedia->removeElement($categorieMedium);

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getMediaLanguage(): Collection
    {
        return $this->mediaLanguage;
    }

    public function addMediaLanguage(Language $mediaLanguage): static
    {
        if (!$this->mediaLanguage->contains($mediaLanguage)) {
            $this->mediaLanguage->add($mediaLanguage);
        }

        return $this;
    }

    public function removeMediaLanguage(Language $mediaLanguage): static
    {
        $this->mediaLanguage->removeElement($mediaLanguage);

        return $this;
    }
}