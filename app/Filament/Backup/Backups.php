<?php
 
namespace App\Filament\Backup;
 
use ShuvroRoy\FilamentSpatieLaravelBackup\Pages\Backups as BaseBackups;
use Illuminate\Support\Facades\Artisan;
use Filament\Actions\Action;


class Backups extends BaseBackups
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
 
    protected function getActions(): array
    {
        return [];
    }

}