<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ConsoleCountTest extends TestCase {
    public function testConsoleCommandCountsCategoriesCorrectly() {
        $this->artisan('app:count-category')->assertSuccessful();
        $mysql_count = intval(DB::table('categories')->selectRaw('count(distinct id) as qty')->get()[0]->qty);
        $redis_count = intval(Redis::get('count_category'));
        $this->assertEquals($mysql_count, $redis_count);
    }

    public function testConsoleCommandCountsBooksCorrectly() {
        $this->artisan('app:count-book')->assertSuccessful();
        $mysql_count = intval(DB::table('books')->selectRaw('count(distinct id) as qty')->get()[0]->qty);
        $redis_count = intval(Redis::get('count_book'));
        $this->assertEquals($mysql_count, $redis_count);
    }

    public function testConsoleCommandCountsExemplarsCorrectly() {
        $this->artisan('app:count-exemplar')->assertSuccessful();
        $mysql_count = intval(DB::table('exemplars')->selectRaw('count(distinct id) as qty')->get()[0]->qty);
        $redis_count = intval(Redis::get('count_exemplar'));
        $this->assertEquals($mysql_count, $redis_count);
    }
}