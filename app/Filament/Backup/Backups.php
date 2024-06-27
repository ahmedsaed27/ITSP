<?php

namespace App\Filament\Backup;

use App\Models\User;
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;
use Illuminate\Support\Facades\Artisan;
use Filament\Actions\Action;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class Backups extends BaseBackups
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected function getActions(): array
    {
        return [];
    }

}
