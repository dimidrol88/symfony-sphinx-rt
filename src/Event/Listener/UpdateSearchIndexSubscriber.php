<?php

declare(strict_types=1);

namespace App\Event\Listener;

use App\Event\Search\CreatedEvent;
use App\Event\Search\DeletedEvent;
use App\Event\Search\UpdatedEvent;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateSearchIndexSubscriber implements EventSubscriberInterface
{
    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreatedEvent::class => 'onEntityCreated',
            UpdatedEvent::class => 'onEntityUpdated',
            DeletedEvent::class => 'onEntityDeleted',
        ];
    }

    public function onEntityCreated(CreatedEvent $event): void
    {
        try {
            $entity = $event->getEntity();

            $this->connection->insert($event->getIndexName(), $entity->getDataForSave(), $entity->getTypeFields());
        } catch (Exception $exception) {
            $this->logger->error('Create failed: ' . $exception->getMessage());
        }
    }

    public function onEntityUpdated(UpdatedEvent $event): void
    {
        try {
            $entity = $event->getEntity();

            $this->connection->delete($event->getIndexName(), $event->getCriteria(), $entity->getTypeFields());

            $this->connection->insert($event->getIndexName(), $entity->getDataForSave(), $entity->getTypeFields());
        } catch (Exception $exception) {
            $this->logger->error('Index do not updated: ' . $exception->getMessage());
        }
    }

    public function onEntityDeleted(DeletedEvent $event): void
    {
        try {
            $entity = $event->getEntity();

            $this->connection->delete($event->getIndexName(), $event->getCriteria(), $entity->getTypeFields());
        } catch (Exception $exception) {
            $this->logger->error('Index do not deleted: ' . $exception->getMessage());
        }
    }
}
