<?php

namespace App\Services\News\Contracts;

use DateTimeImmutable;

abstract class Puller
{
    abstract public function getAddapter(): Adapter;
    abstract public function pull(
        string $category = null,
        int $page = 1,
        int $perPage = 100,
        ?string $query = null,
        ?array $authors = [],
        ?DateTimeImmutable $from = null,
        ?DateTimeImmutable $to = null,
    ): array;
}