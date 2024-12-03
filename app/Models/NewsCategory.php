<?php

namespace App\Models;

use App\Services\News\Contracts\NewsCategory as NewsCategoryContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NewsCategory
 * 
 * @package App\Models
 * 
 * @OA\Schema(
 *     schema="NewsCategory",
 *     type="object",
 *     title="NewsCategory",
 *     description="This class represents a news category model and implements the NewsCategoryContract interface.",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier for the news category."
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the news category."
 *     )
 * )
 */
class NewsCategory extends Model implements NewsCategoryContract
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    
    public function getId(): int
    {
        return $this->getAttribute('id');
    }
    
    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}
