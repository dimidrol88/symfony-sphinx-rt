<?php

declare(strict_types=1);

namespace App\Entity;

interface RealTimeIndexInterface
{
    public function getDataForSave(): array;

    public function getTypeFields(): array;
}