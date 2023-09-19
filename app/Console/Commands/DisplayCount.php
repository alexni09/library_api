<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class DisplayCount extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:display-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the counts of categories, books and exemplars; as previously fetched.';

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->info('# of categories: ' . Redis::get('count_category'));
        $this->info('# of books: ' . Redis::get('count_book'));
        $this->info('# of exemplars: ' . Redis::get('count_exemplar'));
        $this->info('database size: ' . Redis::get('count_mysql'));
        return 0;
    }
}