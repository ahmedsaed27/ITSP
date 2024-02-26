<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public $total;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Total', $this->total->vacations->total ?? 0),
            Stat::make('Expire', $this->total->vacations->expire ?? 0),
            Stat::make('Available', $this->total->vacations->available ?? 0),
        ];
    }
}
