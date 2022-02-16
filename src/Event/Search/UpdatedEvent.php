<?php

declare(strict_types=1);

namespace App\Event\Search;

use App\Entity\RealTimeIndexInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UpdatedEvent extends Event
{
    private RealTimeIndexInterface $entity;
    private string $indexName;
    private ?array $criteria;

    public function __construct(RealTimeIndexInterface $entity, string $indexName, array $criteria = null)
    {
        $this->entity = $entity;
        $this->indexName = $indexName;
        $this->criteria = $criteria;
    }

    public function getEntity(): RealTimeIndexInterface
    {
        return $this->entity;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function getCriteria(): ?array
    {
        return $this->criteria;
    }
}
