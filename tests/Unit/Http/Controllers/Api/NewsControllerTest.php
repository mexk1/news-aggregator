<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Models\News;
use App\Services\News\Contracts\NewsRepository;
use Illuminate\Http\Response;
use Mockery\MockInterface;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    private MockInterface&NewsRepository $newsRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->newsRepository = $this->mock(NewsRepository::class);
    }

    public function testListReturnsJsonResponse(): void
    {
        $this->newsRepository->shouldReceive('search')->andReturn([
            'data' => [],
            'total' => 0,
        ]);

        $response = $this->getJson('api/news', [
            'page' => 1,
            'perPage' => 10,
            'query' => 'test',
            'categories' => 'category1,category2',
            'authors' => 'author1,author2',
            'from' => '2023-01-01',
            'to' => '2023-12-31',
        ]);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testListHandlesException(): void
    {
        $this->newsRepository->shouldReceive('search')->andThrow(new \Exception('Error'));

        $response = $this->getJson('/api/news', [
            'page' => 1,
        ]);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testDetailsReturnsJsonResponse(): void
    {
        $id = 1;
        $this->newsRepository->shouldReceive('getById')->andReturn(News::factory()->create());

        $response = $this->getJson("/api/news/$id");

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testFilters(): void 
    {
        $this->newsRepository->shouldReceive('search')->withArgs(function(...$args){
            $this->assertEquals(1, $args[0],"Expected page to be 1" );
            $this->assertEquals(10, $args[1],"Expected perPage to be 10" );
            $this->assertEquals('test', $args[2],"Expected query to be 'test'" );
            $this->assertEquals(['category1', 'category2'], $args[3],"Expected categories to be ['category1', 'category2']" );
            $this->assertEquals(['author1', 'author2'], $args[4],"Expected authors to be ['author1', 'author2']" );
            $this->assertEquals('2023-01-01', $args[5]->format('Y-m-d'),"Expected from to be '2023-01-01'" );
            $this->assertEquals('2023-12-31', $args[6]->format('Y-m-d'),"Expected to to be '2023-12-31'" );

            return true;
        })->andReturn([
            'data' => [],
            'total' => 0,
        ]);

        $response = $this->getJson('api/news?' . http_build_query([
            'page' => 1,
            'perPage' => 10,
            'query' => 'test',
            'categories' => 'category1,category2',
            'authors' => 'author1,author2',
            'from' => '2023-01-01',
            'to' => '2023-12-31',
        ]));

        $response->assertOk();
    }
}