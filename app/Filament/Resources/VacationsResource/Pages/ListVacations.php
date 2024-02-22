<?php

namespace App\Filament\Resources\VacationsResource\Pages;

use App\Filament\Resources\VacationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVacations extends ListRecords
{
    protected static string $resource = VacationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
