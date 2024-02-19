<?php

namespace App\Filament\Hr\Resources\SkilsResource\Pages;

use App\Filament\Hr\Resources\SkilsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkils extends ListRecords
{
    protected static string $resource = SkilsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
