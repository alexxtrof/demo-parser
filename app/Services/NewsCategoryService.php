<?php

namespace App\Services;

use App\Models\NewsCategory;
use Str;

class NewsCategoryService
{
    /**
     * Создаем категорию, если она не существует
     *
     * @param string $title
     * @return NewsCategory
     */
    public function createByTitle(string $title): NewsCategory
    {
        $slug = Str::slug($title);

        $category = NewsCategory::firstOrCreate(['slug' => $slug], [
            'title' => $title,
        ]);

        return $category;
    }
}
