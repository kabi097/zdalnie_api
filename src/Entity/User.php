<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY', object)",
 *              "validation_groups"={"Default", "create"}
 *          },
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"security"="is_granted('ROLE_USER') and object == user"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}}
 * )
 * @UniqueEntity("email", message="Ten adres email jest już zarejestrowany")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read","user:write"})
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"user:read", "user:write", "post:read"})
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write", "post:read", "reply:read"})
     */
    private $publicEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write", "post:read"})
     */
    private $publicPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write", "post:read"})
     */
    private $publicAddress;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read","user:write", "post:read", "reply:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read", "post:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user")
     * @Groups({"user:read"})
     * @ApiSubresource()
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reply", mappedBy="user")
     * @Groups({"user:read"})
     * @ApiSubresource()
     */
    private $replies;

    /**
     * @SerializedName("password")
     * @Groups({"user:write"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $github;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read","user:write"})
     */
    private $youtube;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->replies = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @Groups({"user:read", "post:read", "reply:read"})
     */
    public function getLogin(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        if ($this->getEmail() == 'admin@admin.pl') {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword() : string
    {
        return $this->plainPassword ?? "";
    }

    public function setPlainPassword($plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPublicEmail(): ?string
    {
        return $this->publicEmail;
    }

    public function setPublicEmail(?string $publicEmail): self
    {
        $this->publicEmail = $publicEmail;

        return $this;
    }

    public function getPublicPhone(): ?string
    {
        return $this->publicPhone;
    }

    public function setPublicPhone(?string $publicPhone): self
    {
        $this->publicPhone = $publicPhone;

        return $this;
    }

    public function getPublicAddress(): ?string
    {
        return $this->publicAddress;
    }

    public function setPublicAddress(?string $publicAddress): self
    {
        $this->publicAddress = $publicAddress;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reply[]
     * @Groups({"user:read"})
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Reply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setUser($this);
        }

        return $this;
    }

    public function removeReply(Reply $reply): self
    {
        if ($this->replies->contains($reply)) {
            $this->replies->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getUser() === $this) {
                $reply->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @Groups({"user:read", "post:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->locale('pl')->diffForHumans();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }
}
