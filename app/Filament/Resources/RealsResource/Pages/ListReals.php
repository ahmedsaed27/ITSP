<?php

namespace App\Filament\Resources\RealsResource\Pages;

use App\Filament\Resources\RealsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReals extends ListRecords
{
    protected static string $resource = RealsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
