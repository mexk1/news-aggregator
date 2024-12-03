<?php 


namespace App\Services\News;

use App\Services\News\Contracts\NewsRepository;

class CreateNews  
{
    public function __construct(
        private NewsRepository $newsRepository
    )
    {
    }

    public function run(array $news): array
    {
        return array_map(
            fn($item) => $this->newsRepository->createNews($item),
            $news
        );
    }
}