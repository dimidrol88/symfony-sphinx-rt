<?php

declare(strict_types=1);

namespace App\Service\Search;

use App\Service\Search\Model\Response;
use Doctrine\DBAL\Connection;

class SphinxSearchService
{
    public const LIMIT = 20;

    private Connection $connection;
    private Response $response;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->response = new Response();
    }

    public function findAll(string $phrase): Response
    {
        $tags = $this->findTags($phrase);

        $this->response->setTags($tags);

        return $this->response;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function findTags(string $phrase): ?array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('title', 'slug')
            ->from('idx_blog_tags')
            ->where("MATCH ( :searchQuery )")
            ->setParameter('searchQuery', $this->preparePhrase($phrase))
            ->setMaxResults(20);

        $sql = $qb->getSQL() . ' OPTION ranker=WORDCOUNT';

        return $this->connection->fetchAllAssociative($sql, $qb->getParameters());
    }

    private function preparePhrase(string $phrase): string
    {
        return str_replace('@', '', $phrase);
    }
}