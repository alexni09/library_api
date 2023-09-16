<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use App\Services\Misc;

class ClearAllRedisKeys extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-all-redis-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Self-explanatory.)';

    /**
     * Execute the console command.
     */
    public function handle() {
        Redis::multi();
        Redis::del(Misc::LIST_DATETIME);
        Redis::del(Misc::LIST_IP);
        Redis::del(Misc::LIST_METHOD);
        Redis::del(Misc::LIST_STATUS);
        Redis::del(Misc::LIST_URL);
        Redis::del(Misc::MONEY);
        Redis::del('count_book');
        Redis::del('count_category');
        Redis::del('count_exemplar');
        Redis::exec();
    }
}