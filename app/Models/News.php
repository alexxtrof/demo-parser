<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Builder
 */
class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'published_at',
        'image',
        'text',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $with = [
        'category',
    ];

    /**
     * Категория новости
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }

    /**
     * Поиск новости по комбинации слага и даты публикации
     * (т.к. при парсинге могут быть получены две новости с одинаковыми заголовками, но разным содержанием).
     *
     * @param Builder $query
     * @param string $slug
     * @param Carbon $publishedAt
     * @return Builder
     */
    public function scopeFindUnique(Builder $query, string $slug, Carbon $publishedAt)
    {
        return $query
            ->where('slug', $slug)
            ->where('published_at', $publishedAt);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function createWithCategory(array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        $item = News::findUnique($data['slug'], $data['published_at'])->first();

        if (!$item) {
            $category = NewsCategory::createByTitle($data['category']);

            $item = new News($data);
            $item->category()->associate($category);
            $item->save();
        }

        return $item;
    }
}
