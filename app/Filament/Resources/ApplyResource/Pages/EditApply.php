<?php

namespace App\Filament\Resources\ApplyResource\Pages;

use App\Filament\Resources\ApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApply extends EditRecord
{
    protected static string $resource = ApplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
