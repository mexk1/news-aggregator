<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\News\Contracts\NewsSource as NewsSourceContract;
use App\Services\News\Contracts\Puller;
use App\Services\News\Sources\NewsApi\NewsApiClient;
use App\Services\News\Sources\NewsApi\NewsApiPuller;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class NewsSource
 *
 * @package App\Models
 *
 * @OA\Schema(
 *     description="NewsSource model",
 *     type="object",
 *     title="NewsSource",
 *     required={"external_id", "name", "is_enabled"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of the news source"
 *     ),
 *     @OA\Property(
 *         property="external_id",
 *         type="string",
 *         description="The external identifier of the news source"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the news source"
 *     ),
 *     @OA\Property(
 *         property="is_enabled",
 *         type="boolean",
 *         description="Indicates if the news source is enabled"
 *     )
 * )
 */
class NewsSource extends Model implements NewsSourceContract
{
    use HasFactory;
    
    protected $fillable = [
        'external_id',
        'name',
        'is_enabled',
    ];

    public function getId(): int
    {
        return $this->getAttribute("id");
    }

    public function getExternalId(): int|string|null
    {
        return $this->getAttribute("external_id");
    }

    public function getName(): string
    {
        return $this->getAttribute("name");
    }

    public function isEnabled(): bool
    {
        return $this->getAttribute("is_enabled");
    }

    public function getPuller(): Puller
    {
        return match ($this->getName()) {
            'news_api' => new NewsApiPuller(app(NewsApiClient::class)),
            default => throw new \Exception('Unknown puller type'),
        };
    }
}
