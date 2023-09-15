<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CountBook extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counts the # of books and stores that number in Redis.';

    /**
     * Execute the console command.
     */
    public function handle() {
        $count = DB::table('books')->selectRaw('count(distinct id) as qty')->get()[0]->qty;
        Redis::set('count_book', $count);
    }
}