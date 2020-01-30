<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"post:read"}},
 *     denormalizationContext={"groups"={"post:write"}}
 * )
 * @ApiFilter()
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
     * @Groups({"post:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post:read", "post:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"post:read", "post:write"})
     */
    private $budget;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = true;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", columnDefinition="DATETIME on update CURRENT_TIMESTAMP")
     * @Groups({"post:read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reply", mappedBy="post")
     */
    private $replies;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="posts")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    public function __construct(string $title = null, string $description = null, float $price = null)
    {
        $this->replies = new ArrayCollection();
        $this->tags = new ArayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
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
     * @Groups({"post:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this.$this->getCreatedAt())->diffForHumans();
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
}
