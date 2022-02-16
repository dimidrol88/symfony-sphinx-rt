<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags", schema="public")
 */
class Tag implements RealTimeIndexInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    private ?int $id;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private string $slug;
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $title;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $created;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $updated;

    public function __construct()
    {
        $this->created = new DateTimeImmutable();
        $this->updated = new DateTimeImmutable();
    }

    public static function create(string $slug, string $title): self
    {
        $tag = new self();

        $tag->slug = $slug;
        $tag->title = $title;

        return $tag;
    }

    public function edit(string $slug, string $title)
    {
        $this->slug = $slug;
        $this->title = $title;
        $this->updated = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): DateTimeImmutable
    {
        return $this->updated;
    }

    public function getDataForSave(): array
    {
        return [
            'id' => $this->getId(),
            'slug' => $this->getSlug(),
            'title' => $this->getTitle()
        ];
    }

    public function getTypeFields(): array
    {
        return [
            'id' => ParameterType::INTEGER,
            'slug' => ParameterType::STRING,
            'title' => ParameterType::STRING
        ];
    }
}