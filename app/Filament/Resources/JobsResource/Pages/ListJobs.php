<?php

namespace App\Filament\Resources\JobsResource\Pages;

use App\Filament\Resources\JobsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobs extends ListRecords
{
    protected static string $resource = JobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
