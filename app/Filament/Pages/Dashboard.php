<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as FilamentDashboard;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class Dashboard extends FilamentDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';


    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
        ];
    }
}
