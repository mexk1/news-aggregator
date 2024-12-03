<?php 

namespace App\Services\News\Contracts;

interface NewsSource
{
    public function getId(): int;
    public function getExternalId(): null|int|string;
    public function getName(): string;
    public function isEnabled(): bool;
    public function getPuller(): Puller;
}