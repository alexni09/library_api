<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class Misc {
    const MAX_MONITORED_LINES = 25;

    public static function monitor(string $method, int $status):void {
        $l = intval(Redis::llen('list_url'));
        Redis::multi();
        Redis::lpush('list_method',Str::upper($method));
        Redis::lpush('list_url',request()->fullUrl());
        Redis::lpush('list_status',strval($status));
        if ($l > self::MAX_MONITORED_LINES) {
            Redis::rpop('list_method', $l - self::MAX_MONITORED_LINES + 1);
            Redis::rpop('list_url', $l - self::MAX_MONITORED_LINES + 1);
            Redis::rpop('list_status', $l - self::MAX_MONITORED_LINES + 1);
        }
        Redis::exec();
    } 

    public static function rating():int {
        $r = rand(1,15);
        switch($r) {
            case 1: return 1; break;
            case 2: case 3: return 2; break;
            case 4: case 5: case 6: return 3; break;
            case 7: case 8: case 9: case 10: return 4; break;
            default: return 5; break;
        }
    }
}