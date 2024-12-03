<?php

namespace App\Services\News;

use App\Models\NewsCategory as NewsCategoryModel;
use App\Services\News\Contracts\NewsCategory;

abstract class NewsCategoryBuilder
{
    public static function build(
        ?string $name = null,
    ): NewsCategory
    {
        return new NewsCategoryModel(compact('name'));
    }
}
