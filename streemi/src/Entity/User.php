<?php

namespace App\Entity;

use App\Enum\UserAccountStatusEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(enumType: UserAccountStatusEnum::class)]
    private ?UserAccountStatusEnum $accountStatus = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Subscription $currentSubscription = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'publisher')]
    private Collection $comments;

    /**
     * @var Collection<int, SubscriptionHistory>
     */
    #[ORM\OneToMany(targetEntity: SubscriptionHistory::class, mappedBy: 'userId')]
    private Collection $subscriptionId;

    /**
     * @var Collection<int, PlaylistSubscription>
     */
    #[ORM\OneToMany(targetEntity: PlaylistSubscription::class, mappedBy: 'user')]
    private Collection $userPlaylist;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'userId')]
    private Collection $commentList;

    /**
     * @var Collection<int, WatchHistory>
     */
    #[ORM\OneToMany(targetEntity: WatchHistory::class, mappedBy: 'userId')]
    private Collection $lastHistory;




    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetToken = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->subscriptionId = new ArrayCollection();
        $this->userPlaylist = new ArrayCollection();
        $this->commentList = new ArrayCollection();
        $this->lastHistory = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
        $this->accountStatus = UserAccountStatusEnum::ACTIVE; // ou une autre valeur par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getAccountStatus(): ?UserAccountStatusEnum
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(UserAccountStatusEnum $accountStatus): static
    {
        $this->accountStatus = $accountStatus;

        return $this;
    }

    public function getCurrentSubscription(): ?Subscription
    {
        return $this->currentSubscription;
    }

    public function setCurrentSubscription(?Subscription $currentSubscription): static
    {
        $this->currentSubscription = $currentSubscription;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPublisher($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getPublisher() === $this) {
                $comment->setPublisher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SubscriptionHistory>
     */
    public function getSubscriptionId(): Collection
    {
        return $this->subscriptionId;
    }

    public function addSubscriptionId(SubscriptionHistory $subscriptionId): static
    {
        if (!$this->subscriptionId->contains($subscriptionId)) {
            $this->subscriptionId->add($subscriptionId);
            $subscriptionId->setSubscriber($this);
        }

        return $this;
    }

    public function removeSubscriptionId(SubscriptionHistory $subscriptionId): static
    {
        if ($this->subscriptionId->removeElement($subscriptionId)) {
            if ($subscriptionId->getSubscriber() === $this) {
                $subscriptionId->setSubscriber(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlaylistSubscription>
     */
    public function getUserPlaylist(): Collection
    {
        return $this->userPlaylist;
    }

    public function addUserPlaylist(PlaylistSubscription $userPlaylist): static
    {
        if (!$this->userPlaylist->contains($userPlaylist)) {
            $this->userPlaylist->add($userPlaylist);
            $userPlaylist->setUser($this);
        }

        return $this;
    }

    public function removeUserPlaylist(PlaylistSubscription $userPlaylist): static
    {
        if ($this->userPlaylist->removeElement($userPlaylist)) {
            if ($userPlaylist->getUser() === $this) {
                $userPlaylist->setUser(null);
            }
        }

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
            $commentList->setPublisher($this);
        }

        return $this;
    }

    public function removeCommentList(Comment $commentList): static
    {
        if ($this->commentList->removeElement($commentList)) {
            if ($commentList->getPublisher() === $this) {
                $commentList->setPublisher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WatchHistory>
     */
    public function getLastHistory(): Collection
    {
        return $this->lastHistory;
    }

    public function addLastHistory(WatchHistory $lastHistory): static
    {
        if (!$this->lastHistory->contains($lastHistory)) {
            $this->lastHistory->add($lastHistory);
            $lastHistory->setUserId($this);
        }

        return $this;
    }

    public function removeLastHistory(WatchHistory $lastHistory): static
    {
        if ($this->lastHistory->removeElement($lastHistory)) {
            if ($lastHistory->getUserId() === $this) {
                $lastHistory->setUserId(null);
            }
        }

        return $this;
    }

    // Implementing methods from UserInterface and PasswordAuthenticatedUserInterface

    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantir que ROLE_USER est toujours présent
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }


    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? '';
    }


    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}