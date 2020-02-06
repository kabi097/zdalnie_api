<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Validator\IsValidOwner;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={},
 *          "post"={"access_control" = "is_granted('ROLE_USER', previous_object)"}
 *      },
 *     itemOperations={"get"={"normalizationContext"={"groups"={"reply:item:get"}}},
 *          "put"={"access_control"="is_granted('EDIT', previous_object)",},
 *          "delete"={"access_control"="is_granted('EDIT', previous_object)",}
 *     },
 *     normalizationContext={"groups"={"reply:read"}},
 *     denormalizationContext={"groups"={"reply:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ReplyRepository")
 */
class Reply
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"reply:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"reply:read", "reply:write", "post:item:get", "post:read"})
     */
    private $content;

    /**
     * @ORM\Column(type="float")
     * @Groups({"reply:read", "reply:write", "post:item:get", "post:read"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"reply:read", "reply:write", "post:item:get", "post:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"reply:read", "reply:write", "post:item:get"})
     */
    private $isPublished;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"reply:read", "post:item:get", "post:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     * @Groups({"reply:read", "post:item:get"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="replies")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"reply:read", "reply:write"})
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="replies")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @IsValidOwner()
     * @Assert\NotBlank()
     * @Groups({"reply:read", "reply:write", "post:read"})
     */
    private $user;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @Groups({"reply:read", "post:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->locale('pl')->diffForHumans();
    }
}
