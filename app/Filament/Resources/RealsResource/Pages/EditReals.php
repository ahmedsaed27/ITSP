<?php

namespace App\Filament\Resources\RealsResource\Pages;

use App\Filament\Resources\RealsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReals extends EditRecord
{
    protected static string $resource = RealsResource::class;

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
