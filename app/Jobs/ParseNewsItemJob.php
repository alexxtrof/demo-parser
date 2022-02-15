<?php

namespace App\Jobs;

use App\Services\Parser\NewsParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseNewsItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * @var NewsParser
     */
    protected $instance;

    /**
     * @var string
     */
    protected $url;

    /**
     * Create a new job instance.
     *
     * @param NewsParser $instance
     * @param string $url
     */
    public function __construct(NewsParser $instance, string $url)
    {
        $this->instance = $instance;
        $this->url = $url;
        $this->onQueue('parser');
    }

    /**
     * Execute the job.
     *
     * TODO: сохранение в БД
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->instance->parseItem($this->url);
    }
}
