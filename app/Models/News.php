<?php

namespace App\Models;

use App\Models\NewsSource as NewsSourceModel;
use App\Services\News\Contracts\News as NewsContract;
use App\Services\News\Contracts\NewsSource;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class News
 *
 * @package App\Models
 * @OA\Schema(
 *     description="News model",
 *     title="News",
 *     required={"external_id", "publish_date", "title", "content", "source_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the news"
 *     ),
 *     @OA\Property(
 *         property="external_id",
 *         type="string",
 *         description="External ID of the news"
 *     ),
 *     @OA\Property(
 *         property="publish_date",
 *         type="string",
 *         format="date-time",
 *         description="Publish date of the news"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the news"
 *     ),
 *     @OA\Property(
 *         property="content",
 *         type="string",
 *         description="Content of the news"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the news"
 *     ),
 *     @OA\Property(
 *         property="original_url",
 *         type="string",
 *         description="Original URL of the news"
 *     ),
 *     @OA\Property(
 *         property="original_image_url",
 *         type="string",
 *         description="Original image URL of the news"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         description="Author of the news"
 *     ),
 *     @OA\Property(
 *         property="source_id",
 *         type="integer",
 *         description="Source ID of the news"
 *     )
 * )
 */
class News extends Model implements NewsContract
{
    use HasFactory;
    
    protected $fillable = [
        'external_id',
        'publish_date',
        'title',
        'content',
        'description',
        'original_url',
        'original_image_url',
        'author',
        'source_id',
    ];

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getExternalId(): int|string|null
    {
        return $this->getAttribute('external_id');
    }

    public function getPublishDate(): DateTime
    {
        return $this->getAttribute('publish_date');
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getContent(): string
    {
        return $this->getAttribute('content');
    }

    public function getDescription(): ?string
    {
        return $this->getAttribute('description');
    }

    public function getOriginalUrl(): ?string
    {
        return $this->getAttribute('original_url');
    }

    public function getOriginalImageUrl(): ?string
    {
        return $this->getAttribute('original_image_url');
    }

    public function getAuthor(): ?string
    {
        return $this->getAttribute('author');
    }
    
    public function getCategories(): array
    {
        return $this->categories->all();
    }

    public function getSource(): NewsSource
    {
        return $this->source;
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(NewsSourceModel::class, 'source_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(NewsCategory::class);
    }

}
