<?php

namespace App\Services\News;

use App\Models\NewsSource as NewsSourceModel;
use App\Services\News\Contracts\NewsSource;

abstract class NewsSourceBuilder
{
    public static function build(
        ?string $name = null,
        null|int|string $externalId = null,
        bool $enabled = true,
    ): NewsSource
    {
        return new NewsSourceModel([
            'name' => $name,
            'external_id' => $externalId,
            'enabled' => $enabled,
        ]);
    }
}
