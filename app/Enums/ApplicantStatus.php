<?php

namespace App\Enums;


enum ApplicantStatus: int
{
    case New = 0;

    // case Reviewed = 1;

    case Acceptable = 2;

    case Rejected = 3;

    case Priorities = 4;
}
