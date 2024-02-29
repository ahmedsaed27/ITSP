<?php

namespace App\Filament\Resources\FinalInterviewStatusResource\Pages;

use App\Filament\Resources\FinalInterviewStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinalInterviewStatus extends EditRecord
{
    protected static string $resource = FinalInterviewStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
