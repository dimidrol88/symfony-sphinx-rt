<?php

declare(strict_types=1);

namespace App\Event\Search;

use App\Entity\RealTimeIndexInterface;
use Symfony\Contracts\EventDispatcher\Event;

class CreatedEvent extends Event
{
    private RealTimeIndexInterface $entity;
    private string $indexName;

    public function __construct(RealTimeIndexInterface $entity, string $indexName)
    {
        $this->entity = $entity;
        $this->indexName = $indexName;
    }

    public function getEntity(): RealTimeIndexInterface
    {
        return $this->entity;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }
}
