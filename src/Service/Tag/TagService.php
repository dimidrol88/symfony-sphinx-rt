<?php

declare(strict_types=1);

namespace App\Service\Tag;

use App\Entity\Tag;
use App\Event\Search\CreatedEvent;
use App\Event\Search\DeletedEvent;
use App\Event\Search\UpdatedEvent;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class TagService
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private TagRepository $tags;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;

        /** @var TagRepository $entityManager */
        $this->tags = $entityManager->getRepository(Tag::class);
    }

    public function create(string $slug, string $title)
    {
        $tag = Tag::create($slug, $title);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new CreatedEvent($tag, 'idx_blog_tags_rt'));
    }

    public function update(int $id, string $slug, string $title)
    {
        /** @var Tag $tag */
        if (!$tag = $this->tags->find($id)) {
            throw new \DomainException('Tag not found');
        }

        $tag->edit($slug, $title);

        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new UpdatedEvent($tag, 'idx_blog_tags_rt', ['id' => $id]));
    }

    public function delete(int $id)
    {
        /** @var Tag $tag */
        if (!$tag = $this->tags->find($id)) {
            throw new \DomainException('Tag not found');
        }

        $this->entityManager->remove($tag);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new DeletedEvent($tag, 'idx_blog_tags_rt', ['id' => $id]));
    }
}