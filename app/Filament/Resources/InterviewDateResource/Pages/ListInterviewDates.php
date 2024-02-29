<?php

namespace App\Filament\Resources\InterviewDateResource\Pages;

use App\Filament\Resources\InterviewDateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterviewDates extends ListRecords
{
    protected static string $resource = InterviewDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
