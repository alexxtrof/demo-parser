<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsCategory;

class NewsService
{
    /**
     * Создание новости с привязкой категории
     *
     * @param array $data
     * @param NewsCategory $category
     * @return News
     */
    public function createWithCategory(array $data, NewsCategory $category): News
    {
        $item = News::findUnique($data['slug'], $data['published_at'])->first();

        if (!$item) {
            $item = new News($data);
            $item->category()->associate($category);
            $item->save();
        }

        return $item;
    }
}
