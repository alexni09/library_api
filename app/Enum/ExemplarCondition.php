<?php

namespace App\Enum;

enum ExemplarCondition: int {
    case LikeNew = 1;
    case Good = 2;
    case Worn = 3;
    case Damaged = 4;
}