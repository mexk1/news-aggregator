<?php 

namespace App\Services\News\Contracts;

use DateTime;

interface News 
{
    public function getId(): int;
    public function getExternalId(): int|string|null;
    public function getPublishDate(): DateTime;
    public function getTitle(): string;
    public function getContent(): string;
    public function getAuthor(): ?string;
    public function getDescription(): ?string;
    public function getOriginalUrl(): ?string;
    public function getOriginalImageUrl(): ?string;
    public function getCategories(): array;
    public function getSource(): NewsSource;
}