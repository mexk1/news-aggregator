<?php

namespace App\Console\Commands;

use App\Jobs\ImportNewsFromSource;
use App\Models\NewsSource;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class PullNews
 *
 * This command pulls news from specified sources, categories, and authors within a given date range.
 * It supports pagination and allows limiting the number of news items pulled.
 *
 * @package App\Console\Commands
 *
 * @property string $signature The name and signature of the console command.
 * @property string $description The console command description.
 *
 * @method void handle() Execute the console command.
 *
 * Options:
 * @option string sources The sources to pull the news from (comma-separated).
 * @option string categories The categories to pull the news from (comma-separated).
 * @option string authors The authors to pull the news from (comma-separated).
 * @option string from The date to pull the news from (YYYY-MM-DD format).
 * @option string to The date to pull the news to (YYYY-MM-DD format).
 * @option string query The query to search for.
 * @option int page The page number (default: 1).
 * @option int per-page The number of news per page (default: 100).
 * @option int max-count The maximum number of news to pull.
 *
 * Usage:
 * php artisan app:pull-news --sources=source1,source2 --categories=category1,category2 --authors=author1,author2 --from=2023-01-01 --to=2023-12-31 --query=example --page=1 --per-page=100 --max-count=500
 */
class PullNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pull-news 
        {--sources= : The sources to pull the news from}
        {--categories= : The categories to pull the news from}
        {--authors= : The authors to pull the news from}
        {--from= : The date to pull the news from}
        {--to= : The date to pull the news to}
        {--query= : The query to search for}
        {--page=1 : The page number}
        {--per-page=100 : The number of news per page}
        {--max-count= : The maximum number of news to pull}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull news from the sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sources = $this->option('sources');
        $categories = $this->option('categories');
        $authors = $this->option('authors');
        $from = $this->option('from');
        $to = $this->option('to');
        $query = $this->option('query');
        $page = $this->option('page');
        $perPage = $this->option('per-page');
        $maxCount = $this->option('max-count');

        $sources = NewsSource::query()
            ->whereIn('name', explode(',', $sources))
            ->get();
            
        foreach($sources as $source) {
            $this->info("Pulling news from {$source->getName()}");

            foreach(explode(',', $categories) as $category) {
                $this->info("Pulling news from $category category");

                try {
                    ImportNewsFromSource::dispatchSync(
                        $source,
                        category: $category,
                        authors: explode(',', $authors),
                        page: $page,
                        perPage: $perPage,
                        from: $from ? new DateTimeImmutable($from) : null,
                        to: $to ? new DateTimeImmutable($to) : null,
                        query: $query,
                        maxCount: $maxCount,
                    );
                } catch (Exception $exception) {
                    $this->error($exception->getMessage());
                    Log::error($exception->getMessage(), compact('exception'));
                    continue;
                }
            }
        }
    }
}
