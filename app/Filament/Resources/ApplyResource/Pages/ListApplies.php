<?php

namespace App\Filament\Resources\ApplyResource\Pages;

use App\Filament\Resources\ApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplies extends ListRecords
{
    protected static string $resource = ApplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
