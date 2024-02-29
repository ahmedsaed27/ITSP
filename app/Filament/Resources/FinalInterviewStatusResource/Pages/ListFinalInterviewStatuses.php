<?php

namespace App\Filament\Resources\FinalInterviewStatusResource\Pages;

use App\Filament\Resources\FinalInterviewStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinalInterviewStatuses extends ListRecords
{
    protected static string $resource = FinalInterviewStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
