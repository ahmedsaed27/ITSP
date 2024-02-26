<?php

namespace App\Enums;


enum Roles:int
{
    case Admin = 0;
    case Employee = 1;
    case Hr = 2;
    case Modirator = 3;

}
