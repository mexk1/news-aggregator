<?php

namespace Tests\Unit;

use App\Models\NewsCategory;
use Tests\TestCase;

class NewsCategoryTest extends TestCase
{
    public function testGetId()
    {
        $newsCategory = new NewsCategory();
        $newsCategory->setAttribute('id', 1);

        $this->assertEquals(1, $newsCategory->getId());
    }

    public function testGetName()
    {
        $newsCategory = new NewsCategory();
        $newsCategory->setAttribute('name', 'Technology');

        $this->assertEquals('Technology', $newsCategory->getName());
    }
}