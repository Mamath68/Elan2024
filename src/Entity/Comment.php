<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $commented_at = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Publications $publication = null;

    /**
     * @var Collection<int, Answers>
     */
    #[ORM\OneToMany(targetEntity: Answers::class, mappedBy: 'comment')]
    private Collection $answers;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?User $User = null;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->commented_at = new \DateTimeImmutable();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getCommentedAt(): ?\DateTimeImmutable
    {
        return $this->commented_at;
    }

    public function setCommentedAt(\DateTimeImmutable $commented_at): static
    {
        $this->commented_at = $commented_at;

        return $this;
    }

    public function getPublication(): ?Publications
    {
        return $this->publication;
    }

    public function setPublication(?Publications $publication): static
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * @return Collection<int, Answers>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): static
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setComment($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): static
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getComment() === $this) {
                $answer->setComment(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
