<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ImportNewsFromSource;
use App\Services\News\Contracts\NewsSource;
use App\Services\News\Contracts\Puller;
use App\Services\News\CreateNews;
use Tests\TestCase;
use Mockery\MockInterface;

class ImportNewsFromSourceTest extends TestCase
{
    public function test_handle_stops_when_news_is_empty()
    {
        $newsSourceMock = $this->createMock(NewsSource::class);
        $newsSourceMock->method('getPuller')->willReturn($this->getPullerMock([]));

        $createNewsMock = $this->createMock(CreateNews::class);
        $createNewsMock->expects($this->never())->method('run');

        $job = new ImportNewsFromSource($newsSourceMock);
        $job->handle($createNewsMock);
    }

    private function getPullerMock(array $news)
    {
        $pullerMock = $this->mock(Puller::class);
        $pullerMock->shouldReceive('pull')->andReturn($news);

        return $pullerMock;
    }
   
    public function test_handle_stops_when_page_size_is_bigger_than_max_count()
    {
        $news = array_fill(0, 100, 'news_item');
        $newsSourceMock = $this->createMock(NewsSource::class);
        $newsSourceMock->method('getPuller')->willReturn($this->getPullerMock($news));

        $createNewsMock = $this->createMock(CreateNews::class);
        $createNewsMock->expects($this->once())->method('run')->with(array_slice($news, 0, 50));

        $job = new ImportNewsFromSource($newsSourceMock, page: 1, perPage: 100, maxCount: 50);
        $job->handle($createNewsMock);
    }

    public function test_handle_stops_when_page_2_is_greater_than_max_count()
    {
        $newsPage1 = array_fill(0, 100, 'news_item');
        $newsPage2 = array_fill(0, 50, 'news_item');
        $newsSourceMock = $this->createMock(NewsSource::class);
        $newsSourceMock->method('getPuller')->willReturnOnConsecutiveCalls(
            $this->getPullerMock($newsPage1),
            $this->getPullerMock($newsPage2)
        );

        /**
         * @var CreateNews&MockInterface $createNewsMock
         */
        $createNewsMock = $this->mock(CreateNews::class);
        $createNewsMock->shouldReceive('run')->with($newsPage1)->once();
        $createNewsMock->shouldReceive('run')->with(array_slice($newsPage2, 0, 20))->once();

        $job = new ImportNewsFromSource($newsSourceMock, page: 1, perPage: 100, maxCount: 120);
        $job->handle($createNewsMock);
    }
    
    public function test_handle_stops_when_page_2_is_smaller_than_page_size()
    {
        $newsPage1 = array_fill(0, 100, 'news_item');
        $newsPage2 = array_fill(0, 50, 'news_item');
        $newsSourceMock = $this->createMock(NewsSource::class);
        $newsSourceMock->method('getPuller')->willReturnOnConsecutiveCalls(
            $this->getPullerMock($newsPage1),
            $this->getPullerMock($newsPage2)
        );

        /**
         * @var CreateNews&MockInterface $createNewsMock
         */
        $createNewsMock = $this->mock(CreateNews::class);
        $createNewsMock->shouldReceive('run')->with($newsPage1)->once();
        $createNewsMock->shouldReceive('run')->with($newsPage2)->once();

        $job = new ImportNewsFromSource($newsSourceMock, page: 1, perPage: 100, maxCount: null);
        $job->handle($createNewsMock);
    }
        
    public function test_handle_stops_when_remaining_is_zero()
    {
        $news = array_fill(0, 100, 'news_item');
        $newsSourceMock = $this->createMock(NewsSource::class);
        $newsSourceMock->method('getPuller')->willReturn($this->getPullerMock($news));
        
        /**
         * @var CreateNews&MockInterface $createNewsMock
         */
        $createNewsMock = $this->createMock(CreateNews::class);
        $createNewsMock->expects($this->once())->method('run')->with(array_slice($news, 0, 100));

        $job = new ImportNewsFromSource($newsSourceMock, page: 1, perPage: 100, maxCount: 100);
        $job->handle($createNewsMock);
    }

    public function test_handle_stops_when_remaining_is_negative()
    {
        $news = array_fill(0, 100, 'news_item');
        $newsSourceMock = $this->createMock(NewsSource::class);
        $newsSourceMock->method('getPuller')->willReturn($this->getPullerMock($news));
        
        /**
         * @var CreateNews&MockInterface $createNewsMock
         */
        $createNewsMock = $this->createMock(CreateNews::class);
        $createNewsMock->expects($this->once())->method('run')->with(array_slice($news, 0, 50));

        $job = new ImportNewsFromSource($newsSourceMock, page: 1, perPage: 100, maxCount: 50);
        $job->handle($createNewsMock);
    }
}
