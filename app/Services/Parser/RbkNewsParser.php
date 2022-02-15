<?php

namespace App\Services\Parser;

use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class RbkNewsParser extends NewsParser
{
    /**
     * @inheritDoc
     */
    protected function getConfigKey(): string
    {
        return 'rbc';
    }

    /**
     * @inheritDoc
     * @throws InvalidSelectorException
     */
    public function parseItemDate(Document $document, string $selector): Carbon
    {
        // Формат даты: 15 фев, 18:26
        // Парсим дату и задаем часовой пояс
        $date = $document->first($selector)->text();
        $date = Carbon::translateTimeString($date, 'ru', 'en');
        $date = Carbon::parse($date)->shiftTimezone('Europe/Moscow');

        return $date;
    }

    /**
     * @inheritDoc
     * @throws InvalidSelectorException
     */
    public function parseItemText(Document $document, string $selector): string
    {
        // Текст статьи находится в двух блоках
        // Т.к. текст может содержать рекламные блоки, берем только теги <p>
        $blocks = $document->find($selector);
        $text = '';

        foreach ($blocks as $block) {
            $paragraphs = $block->find('p');

            foreach ($paragraphs as $p) {
                $text .= (string) $p;
            }
        }

        return $text;
    }
}
