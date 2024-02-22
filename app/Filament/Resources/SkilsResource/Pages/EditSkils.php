<?php

namespace App\Filament\Resources\SkilsResource\Pages;

use App\Filament\Resources\SkilsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkils extends EditRecord
{
    protected static string $resource = SkilsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
