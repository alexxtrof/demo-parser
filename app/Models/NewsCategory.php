<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

/**
 * @mixin Builder
 */
class NewsCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
    ];

    /**
     * Новости категории
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }

    /**
     * Создаем категорию, если она не существует
     *
     * @param $title
     * @return NewsCategory
     */
    public static function createByTitle($title): NewsCategory
    {
        $slug = Str::slug($title);

        $category = self::firstOrCreate(['slug' => $slug], [
            'title' => $title,
        ]);

        return $category;
    }
}
