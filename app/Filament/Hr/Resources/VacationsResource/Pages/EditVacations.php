<?php

namespace App\Filament\Hr\Resources\VacationsResource\Pages;

use App\Filament\Hr\Resources\VacationsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVacations extends EditRecord
{
    protected static string $resource = VacationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
