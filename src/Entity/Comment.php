<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    normalizationContext: ['groups'=> ['read']],
    denormalizationContext: ['groups'=> ['write']],
    shortName: "commentaires",
    operations: [
        new Get,
        new Post,
        new GetCollection,
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['author' => 'partial'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(RangeFilter::class, properties: ['note'])]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read', 'write'])]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?int $note = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    private ?Conference $conference = null;

    #[Groups(['read'])]
    private ?string $shortText;

    public function __construct()
    {
        $this->setCreatedAt($this->createdAt);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt=null): self
    {   
        if($this->createdAt === null){
            $this->createdAt = new \DateTimeImmutable();
        }else{
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    public function getShortText():?string
    {
        $this->shortText = substr($this->text,0,2);
        return $this->shortText;
    }

}
