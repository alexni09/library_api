<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CountMysqlSize extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:count-mysql-size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Counts the # of GBs in the MySQL database.';

    /**
     * Execute the console command.
     */
    public function handle() {
        $total_sum = DB::table('information_schema.tables')->selectRaw('sum(data_length) + sum(index_length) as total_sum')->first()->total_sum;
        Redis::set('count_mysql', strval(number_format($total_value / 1073741824, 2)) . 'GB');
    }
}