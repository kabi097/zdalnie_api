<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Validator\IsValidOwner;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 *                                                                                                                                                                                                                                                                                                                                                                                                           @ORM\EntityListeners({"App\Doctrine\SetOwnerListener"})
 * @ApiResource(
 *     collectionOperations={
 *          "get"={},
 *          "post"={"security" = "is_granted('ROLE_USER', object)"}
 *      },
 *     itemOperations={"get"={"normalizationContext"={"groups"={"post:item:get"}}},
 *          "put"={"security" = "is_granted('EDIT', object)"},
 *          "delete"={"security"="is_granted('EDIT', object)",}
 *     },
 *     normalizationContext={"groups"={"post:read"}},
 *     denormalizationContext={"groups"={"post:write"}}
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isPublished"})
 * @ApiFilter(SearchFilter::class, properties={"title": "partial", "description": "partial"})
 * @ApiFilter(RangeFilter::class, properties={"budget"})
 * @ApiFilter(OrderFilter::class, properties={"createdAt", "endDate"}, arguments={"orderParameterName"="order"})
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"post:read", "post:write", "reply:read", "user:read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=100, maxMessage="Tytuł może mieć maksymalnie 100 znaków", minMessage="Tytuł musi mieć więcej niż 5 znaków")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post:read", "post:write", "user:read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, minMessage="Musisz użyć co najmniej 5 znaków w Opisie")
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"post:read", "post:write", "user:read"})
     */
    private $budget;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = true;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post:read", "user:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     * @Groups({"post:read", "user:read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post:read", "user:read"})
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reply", mappedBy="post")
     * @Groups({"post:read", "user:read"})
     */
    private $replies;

    /**
     * @Groups({"post:read", "post:write", "user:read"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="posts")
     */
    private $tags;

    /**
     * @Groups({"post:read", "post:write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"post:read", "post:collection:post"})
     * @IsValidOwner()
     * @Assert\NotBlank()
     */
    private $user;

    public function __construct(string $title = null, string $description = null, float $budget = null, int $days = null)
    {
        $this->replies = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->title = $title;
        $this->description = $description;
        $this->budget = $budget;
        if ($days) {
            $this->endDate = $this->createdAt->add(new \DateInterval("P".$days."D"));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }


    /**
     * @Groups("post:read")
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return (strlen($this->description) < 60) ? $this->description : substr($this->description, 0, 60)."...";
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(?float $budget): self
    {
        $this->budget = $budget;

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

    /**
     * @Groups({"post:read", "user:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->locale('pl')->diffForHumans();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|Reply[]
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function addReply(Reply $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setPost($this);
        }

        return $this;
    }

    public function removeReply(Reply $reply): self
    {
        if ($this->replies->contains($reply)) {
            $this->replies->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getPost() === $this) {
                $reply->setPost(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

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
     * @Groups({"post:read", "post:write"})
     */
    public function setDays(int $days): void
    {
        $this->endDate = $this->createdAt->add(new \DateInterval("P".$days."D"));
    }

    /**
     * @Groups({"post:read", "post:write", "user:read"})
     */
    public function getLeftDays(): string
    {
        return Carbon::instance($this->getEndDate())->locale('pl')->diffForHumans();
    }
}
