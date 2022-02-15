<?php

namespace App\Services\Parser;

use App\Helpers\Curl;
use App\Jobs\ParseNewsItemJob;
use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Log;

abstract class NewsParser
{
    /**
     * @var string Имя ключа в конфиге parser.php
     */
    protected $configKey;

    /**
     * @var array
     */
    protected $urls = [];

    public function __construct()
    {
        $this->configKey = $this->getConfigKey();
    }

    /**
     * Запуск парсера
     */
    public function run()
    {
        $this->parseUrls();
        $this->addToQueue();
    }

    /**
     * Получаем ссылки на полную новость
     *
     * @return int Кол-во найденных новостей
     */
    protected function parseUrls()
    {
        $url = $this->getConfig('list.url');
        $html = Curl::getHtmlPage($url);

        if ($html) {
            $document = new Document();
            $document->loadHtml($html);
            $selectors = $this->getConfig('list.selectors');

            if ($document->has($selectors['block'])) {
                try {
                    $elements = $document->first($selectors['block'])->find($selectors['a']);

                    foreach ($elements as $element) {
                        $this->urls[] = $element->getAttribute('href');
                    }

                    return count($elements);

                } catch (InvalidSelectorException $e) {
                    Log::error($e->getMessage(), 'Parser');
                }
            }
        }

        return 0;
    }

    /**
     * Отправка заданий на парсинг в очередь
     */
    protected function addToQueue()
    {
        foreach ($this->urls as $url) {
            ParseNewsItemJob::dispatch(new static(), $url);
        }
    }

    /**
     * Получение настроек из файла конфигурации
     *
     * @param $key
     * @return mixed
     */
    protected function getConfig($key)
    {
        return config("parser.{$this->configKey}.$key");
    }

    /**
     * Ключ настроек
     *
     * @return string Имя ключа в конфиге parser.php
     */
    abstract protected function getConfigKey(): string;

    /**
     * @param string $url
     * @return array|null
     */
    public function parseItem(string $url)
    {
        $html = Curl::getHtmlPage($url);

        if ($html) {
            try {
                $document = new Document();
                $document->loadHtml($html);

                $selectors = $this->getConfig('item.selectors');

                $category = $document->first($selectors['category'])->text();
                $title = $document->first($selectors['title'])->text();
                $image = $document->first($selectors['image']);
                $image = $image ? $image->getAttribute('src') : null;
                $date = $this->parseItemDate($document, $selectors['date']);
                $text = $this->parseItemText($document, $selectors['text']);

                return [
                    'title'    => $title,
                    'category' => $category,
                    'date'     => $date,
                    'image'    => $image,
                    'text'     => $text,
                ];
            } catch (\Exception $e) {
                Log::error([
                    'url' => $url,
                    'error' => $e->getMessage(),
                ], 'Parser');
            }
        }

        return null;
    }

    /**
     * @param Document $document
     * @param string $selector
     * @return Carbon
     */
    abstract public function parseItemDate(Document $document, string $selector): Carbon;

    /**
     * @param Document $document
     * @param string $selector
     * @return string
     */
    abstract public function parseItemText(Document $document, string $selector): string;
}
