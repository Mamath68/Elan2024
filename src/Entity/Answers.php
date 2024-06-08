<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
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
    private ?\DateTimeImmutable $answered_at = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Comment $comment = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?User $user = null;

    public function __construct()
    {
        $this->answered_at = new \DateTimeImmutable();
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

    public function getAnsweredAt(): ?\DateTimeImmutable
    {
        return $this->answered_at;
    }

    public function setAnsweredAt(\DateTimeImmutable $answered_at): static
    {
        $this->answered_at = $answered_at;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
