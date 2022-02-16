<?php

namespace App\Console\Commands;

use App\Services\Parser\NewsParser;
use Illuminate\Console\Command;

class ParseNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News parser using queues';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param NewsParser $newsParser
     */
    public function handle(NewsParser $newsParser)
    {
        $newsParser->run();
    }
}
