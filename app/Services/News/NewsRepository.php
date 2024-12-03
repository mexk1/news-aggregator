<?php 

namespace App\Services\News;

use App\Models\News as NewsModel;
use App\Models\NewsCategory;
use App\Services\News\Contracts\News;
use App\Services\News\Contracts\NewsRepository as ContractsNewsRepository;
use DateTimeImmutable;

class NewsRepository implements ContractsNewsRepository{
  
    public function createNews(News $news): News
    {
        /**
         * @var News&NewsModel $news
         */
        $news = new NewsModel([
            'title' => $news->getTitle(),
            'description' => $news->getDescription(),
            'content' => $news->getContent(),
            'author' => $news->getAuthor(),
        ]);

        $news->save();

        $this->syncCategories($news);

        return $news->refresh();
    }

    public function search(
        int $page = 1,
        int $perPage = 10,
        ?string $query = null,
        ?array $categories = [],
        ?array $authors = [],
        ?DateTimeImmutable $from = null,
        ?DateTimeImmutable $to = null,
    ): array
    {
        $sql = NewsModel::query()
            ->with(['categories', 'source'])
            ->when($categories, fn($query, $categories) => $query->whereHas('categories', fn($query) => $query->whereIn('name', $categories)))
            ->when($authors, fn($query, $authors) => $query->whereIn('author', $authors))
            ->when($from, fn($query, $from) => $query->where('publish_date', '>=', $from))
            ->when($to, fn($query, $to) => $query->where('publish_date', '<=', $to))
            ->when($query, fn($sql, $query) => $sql->where('title', 'like', "%$query%"));
        
        $count = clone $sql;
        $total = $count->count('news.id');

        $offset = ($page - 1) * $perPage;
        
        $items = $sql
            ->offset($offset)
            ->limit($perPage)
            ->orderBy('publish_date', 'desc')
            ->get();

        return compact('page', 'items', 'total');
    }

    public function getById(int $id): News
    {
        return NewsModel::with(['categories', 'source'])->findOrFail($id);
    }

    public function syncCategories(News $news): void
    {
        $categoriesNames = array_map(
            fn(NewsCategory $category) => $category->getName(),
            $news->getCategories(),
        );

        $existingCategories = NewsCategory::query()
            ->whereIn('name', $categoriesNames)
            ->get();
        
        $newCategories = array_diff(
            $categoriesNames,
            $existingCategories->pluck('name')->toArray()
        );

        foreach ($newCategories as $categoryName) {
            $existingCategories->push(NewsCategory::create(['name' => $categoryName]));
        }

        /**
         * @var News&NewsModel $news
         */
        $news->categories()->sync($existingCategories->pluck('id'));
    }
}