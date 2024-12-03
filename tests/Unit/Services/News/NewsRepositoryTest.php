<?php

namespace Tests\Unit\Services\News;

use App\Models\News as NewsModel;
use App\Models\NewsCategory;
use App\Services\News\Contracts\NewsSource;
use App\Services\News\NewsRepository;
use Tests\TestCase;

class NewsRepositoryTest extends TestCase
{
    protected NewsRepository $newsRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsRepository = new NewsRepository();
    }

    public function testCreateNews()
    {
        $newsMock = $this->createMock(NewsModel::class);
        $newsMock->method('getTitle')->willReturn('Test Title');
        $newsMock->method('getDescription')->willReturn('Test Description');
        $newsMock->method('getContent')->willReturn('Test Content');
        $newsMock->method('getAuthor')->willReturn('Test Author');
        $newsMock->method('getCategories')->willReturn([]);
        $newsMock->method('getSource')->willReturn($this->createMock(NewsSource::class));

        $news = $this->newsRepository->createNews($newsMock);

        $this->assertInstanceOf(NewsModel::class, $news);
        $this->assertDatabaseHas('news', [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'content' => 'Test Content',
            'author' => 'Test Author'
        ]);
    }

    public function testSearch()
    {
        NewsModel::factory()->count(15)->create();

        $result = $this->newsRepository->search(1, 10);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('items', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertCount(10, $result['items']);
        $this->assertEquals(15, $result['total']);
    }

    public function testGetById()
    {
        $news = NewsModel::factory()->create();

        $foundNews = $this->newsRepository->getById($news->id);

        $this->assertInstanceOf(NewsModel::class, $foundNews);
        $this->assertEquals($news->id, $foundNews->id);
    }

    public function testSyncCategories()
    {
        $category = NewsCategory::factory()->create(['name' => 'Test Category']);
        $newCategory = NewsCategory::factory()->make(['name' => 'New Category']);

        /**
         * @var NewsModel $news
         */
        $news = NewsModel::factory()->createOne();
        $news->setRelation('categories', collect([$category, $newCategory]));

        $this->newsRepository->syncCategories($news);

        $this->assertDatabaseHas('news_categories', ['name' => 'Test Category']);
        $this->assertDatabaseHas('news_news_category', [
            'news_id' => $news->id,
            'news_category_id' => $category->id
        ]);
        $this->assertDatabaseHas('news_categories', ['name' => 'New Category']);
        $this->assertDatabaseHas('news_news_category', [
            'news_id' => $news->id,
            'news_category_id' => NewsCategory::where('name', 'New Category')->first()->id
        ]);
    }
    
}