<?php

declare(strict_types=1);

namespace App\Service\Search\Model;

class Response
{
    protected int $total = 0;
    protected array $errors = [];
    protected array $tags = [];

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
        $this->setTotal(count($tags));
    }
}