<?php

namespace Tests\Unit\Models;

use App\Models\NewsSource;
use App\Services\News\Sources\NewsApi\NewsApiClient;
use App\Services\News\Sources\NewsApi\NewsApiPuller;
use Tests\TestCase;

class NewsSourceTest extends TestCase
{
    public function test_can_get_id()
    {
        $newsSource = NewsSource::factory()->create(['id' => 1]);
        $this->assertEquals(1, $newsSource->getId());
    }

    public function test_can_get_external_id()
    {
        $newsSource = NewsSource::factory()->create(['external_id' => 'ext123']);
        $this->assertEquals('ext123', $newsSource->getExternalId());
    }

    public function test_can_get_name()
    {
        $newsSource = NewsSource::factory()->create(['name' => 'news_api']);
        $this->assertEquals('news_api', $newsSource->getName());
    }

    public function test_can_check_if_enabled()
    {
        $newsSource = NewsSource::factory()->create(['is_enabled' => true]);
        $this->assertTrue($newsSource->isEnabled());
    }

    public function test_can_get_puller()
    {
        $newsSource = NewsSource::factory()->create(['name' => 'news_api']);
        $this->mock(NewsApiClient::class);
        $puller = $newsSource->getPuller();
        $this->assertInstanceOf(NewsApiPuller::class, $puller);
    }

    public function test_throws_exception_for_unknown_puller()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown puller type');

        $newsSource = NewsSource::factory()->create(['name' => 'unknown']);
        $newsSource->getPuller();
    }
}
