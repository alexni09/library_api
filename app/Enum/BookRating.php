<?php

namespace App\Enum;

enum BookRating: int {
    case Awful = 1;
    case Bad = 2;
    case Reasonable = 3;
    case Good = 4;
    case Excelent = 5;
}