<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use App\Services\Misc;

class ConsoleClearAllRedisKeysTest extends TestCase {
    public function testConsoleCommandClearsAllRedisKeysThatItShould() {
        $this->artisan('app:clear-all-redis-keys')->assertSuccessful();
        $redis_keys = Redis::keys('*');
        $this->assertNotContains('library_api_database_count_category', $redis_keys);
        $this->assertNotContains('library_api_database_count_book', $redis_keys);
        $this->assertNotContains('library_api_database_count_exemplar', $redis_keys);
        $this->assertNotContains('library_api_database_' . Misc::LIST_DATETIME, $redis_keys);
        $this->assertNotContains('library_api_database_' . Misc::LIST_IP, $redis_keys);
        $this->assertNotContains('library_api_database_' . Misc::LIST_METHOD, $redis_keys);
        $this->assertNotContains('library_api_database_' . Misc::LIST_STATUS, $redis_keys);
        $this->assertNotContains('library_api_database_' . Misc::LIST_URL, $redis_keys);
        $this->assertNotContains('library_api_database_' . Misc::MONEY, $redis_keys);
    }
}