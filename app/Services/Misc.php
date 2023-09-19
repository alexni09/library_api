<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Misc {
    const LIST_DATETIME = 'list_datetime';
    const LIST_METHOD = 'list_method';
    const LIST_STATUS = 'list_status';
    const LIST_URL = 'list_url';
    const LIST_IP = 'list_ip';
    const MONEY = 'money';

    public static function accumulatedMoney():int {
        return intval(Redis::get(self::MONEY));
    }

    public static function condition():int {
        $r = rand(1,10);
        switch($r) {
            case 1: return 4; break;
            case 2: case 3: return 3; break;
            case 4: case 5: case 6: return 2; break;
            default: return 1; break;
        }
    }
    public static function list_datetime():array {
        return Redis::lrange(self::LIST_DATETIME,0,-1);
    }

    public static function list_ip():array {
        return Redis::lrange(self::LIST_IP,0,-1);
    }

    public static function list_method():array {
        return Redis::lrange(self::LIST_METHOD,0,-1);
    }

    public static function list_status():array {
        return Redis::lrange(self::LIST_STATUS,0,-1);
    }

    public static function list_url():array {
        return Redis::lrange(self::LIST_URL,0,-1);
    }

    public static function makeMoney(int $money):void {
        Redis::incrby(self::MONEY, $money);
    }

    public static function monitor(string $method, int $status):void {
        $l = 1 + intval(Redis::llen('list_url'));
        Redis::multi();
        Redis::lpush(self::LIST_DATETIME,strval(Carbon::now()));
        Redis::lpush(self::LIST_IP,request()->ip());
        Redis::lpush(self::LIST_METHOD,Str::upper($method));
        Redis::lpush(self::LIST_URL,request()->fullUrl());
        Redis::lpush(self::LIST_STATUS,strval($status));
        if ($l > env('MAX_MONITORED_LINES',60)) {
            Redis::rpop(self::LIST_DATETIME, $l - env('MAX_MONITORED_LINES',60));
            Redis::rpop(self::LIST_IP, $l - env('MAX_MONITORED_LINES',60));
            Redis::rpop(self::LIST_METHOD, $l - env('MAX_MONITORED_LINES',60));
            Redis::rpop(self::LIST_URL, $l - env('MAX_MONITORED_LINES',60));
            Redis::rpop(self::LIST_STATUS, $l - env('MAX_MONITORED_LINES',60));
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