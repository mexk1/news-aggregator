<?php 

namespace App\Services\News\Contracts;

use DateTimeImmutable;

interface NewsRepository {
    public function createNews(News $news): News;
    public function search(
        int $page = 1,
        int $perPage = 10,
        ?string $query = null,
        ?array $categories = [],
        ?array $authors = [],
        ?DateTimeImmutable $from = null,
        ?DateTimeImmutable $to = null,
    ): array;
    public function getById(int $id): News;
}