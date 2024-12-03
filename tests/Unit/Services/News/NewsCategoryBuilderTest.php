<?php

namespace Tests\Unit\Services\News;

use App\Services\News\NewsCategoryBuilder;
use App\Models\NewsCategory as NewsCategoryModel;
use Tests\TestCase;

class NewsCategoryBuilderTest extends TestCase
{
    public function testBuildWithName()
    {
        $name = 'Technology';
        $newsCategory = NewsCategoryBuilder::build($name);

        $this->assertInstanceOf(NewsCategoryModel::class, $newsCategory);
        $this->assertEquals($name, $newsCategory->name);
    }

    public function testBuildWithoutName()
    {
        $newsCategory = NewsCategoryBuilder::build();

        $this->assertInstanceOf(NewsCategoryModel::class, $newsCategory);
        $this->assertNull($newsCategory->name);
    }
}