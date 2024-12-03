<?php 

namespace App\Services\News\Contracts;

interface NewsCategory
{
    public function getId(): int;
    public function getName(): string;
}