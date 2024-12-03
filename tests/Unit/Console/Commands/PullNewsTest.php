<?php

namespace Tests\Console\Commands;

use App\Jobs\ImportNewsFromSource;
use App\Models\NewsSource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class PullNewsTest extends TestCase
{
    /**
     * Test the handle method of the console command.
     *
     * @return void
     */
    public function testHandle()
    {
        Bus::fake();

        NewsSource::factory()->createMany([
            ['name' => 'source1'],
            ['name' => 'source2'],
        ]);

        $this->artisan('app:pull-news', [
            '--sources' => 'source1,source2',
            '--categories' => 'category1,category2',
            '--authors' => 'author1,author2',
            '--from' => '2023-01-01',
            '--to' => '2023-12-31',
            '--query' => 'test query',
            '--page' => 1,
            '--per-page' => 100,
            '--max-count' => 10,
        ])->assertExitCode(Command::SUCCESS);

        Bus::assertDispatchedSync(ImportNewsFromSource::class, function ($job) {
            return $job->source->name === 'source1' && $job->category === 'category1';
        });

        Bus::assertDispatchedSync(ImportNewsFromSource::class, function ($job) {
            return $job->source->name === 'source2' && $job->category === 'category2';
        });

        Bus::assertNothingDispatched();
    }
}