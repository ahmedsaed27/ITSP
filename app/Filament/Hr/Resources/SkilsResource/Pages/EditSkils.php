<?php

namespace App\Filament\Hr\Resources\SkilsResource\Pages;

use App\Filament\Hr\Resources\SkilsResource;
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
