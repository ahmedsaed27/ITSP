<?php

namespace App\Helpers;

use Carbon\Carbon;

if(!function_exists('calculation')){
    function calculation($date)
    {
        list($startDate, $endDate) = explode(' - ', $date);

        $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
        $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);
        $diffInDays = $carbonStartDate->diffInDays($carbonEndDate);
        return $diffInDays;
    }
}