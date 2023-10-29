<?php

namespace App\Entity;

use App\Repository\EmailLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailLogRepository::class)]
class EmailLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sentAt = null;

    #[ORM\ManyToOne(inversedBy: 'emailLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recipient = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contentHtml = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentText = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $typeDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getContentHtml(): ?string
    {
        return $this->contentHtml;
    }

    public function setContentHtml(string $contentHtml): static
    {
        $this->contentHtml = $contentHtml;

        return $this;
    }

    public function getContentText(): ?string
    {
        return $this->contentText;
    }

    public function setContentText(?string $contentText): static
    {
        $this->contentText = $contentText;

        return $this;
    }

    public function getTypeDescription(): ?string
    {
        return $this->typeDescription;
    }

    public function setTypeDescription(?string $typeDescription): static
    {
        $this->typeDescription = $typeDescription;

        return $this;
    }
}
