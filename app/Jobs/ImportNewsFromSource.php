<?php

namespace App\Jobs;

use App\Services\News\Contracts\NewsSource;
use App\Services\News\CreateNews;
use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ImportNewsFromSource implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly NewsSource $source,
        public readonly int $page = 1,
        public readonly int $perPage = 100,
        public readonly ?int $maxCount = null,
        public readonly ?string $category = null,
        public readonly ?string $query = null,
        public readonly ?array $authors = [],
        public readonly ?DateTimeImmutable $from = null,
        public readonly ?DateTimeImmutable $to = null,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(
        CreateNews $createNews,
    ): void
    {
        $page = $this->page;

        do {
            $news = $this->source->getPuller()->pull(
                page: $page,
                perPage: $this->perPage,
                category: $this->category,
                query: $this->query,
                authors: $this->authors,
            );

            if (empty($news)) {
                break;
            }
            
            if ($this->maxCount !== null) {
                $remaining = $this->maxCount - (($page - 1) * $this->perPage);

                if ($remaining <= 0) {
                    break;
                }
             
                $news = array_slice($news, 0, min($remaining, $this->perPage));
            }

            $createNews->run($news);

            $page++;
        } while (count($news) === $this->perPage);
    }
}
