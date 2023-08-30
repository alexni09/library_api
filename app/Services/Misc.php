<?php

namespace App\Services;

class Misc {
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