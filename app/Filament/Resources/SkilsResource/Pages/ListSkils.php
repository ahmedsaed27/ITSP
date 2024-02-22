<?php

namespace App\Filament\Resources\SkilsResource\Pages;

use App\Filament\Resources\SkilsResource;
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
