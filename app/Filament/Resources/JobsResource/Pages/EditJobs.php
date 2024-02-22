<?php

namespace App\Filament\Resources\JobsResource\Pages;

use App\Filament\Resources\JobsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobs extends EditRecord
{
    protected static string $resource = JobsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
