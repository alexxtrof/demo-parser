<?php

/**
 * 1) В ключе не должно быть точки
 * 2) Для ссылок нужно указывать классы (для фильтрации рекламных ссылок)
 */
return [
    'rbc' => [
        'list' => [
            'url'      => 'https://kuban.rbc.ru',
            'selectors' => [
                'block' => '.js-news-feed-list',
                'a'     => '.news-feed__item',
            ],
        ],
        'item' => [
            'selectors' => [
                'category' => '.article__header__category',
                'title'    => 'h1',
                'date'     => '.article__header__date',
                'image'    => '.article__main-image__image',
                'text'     => '.article__text',
            ],
        ],
    ],
];
