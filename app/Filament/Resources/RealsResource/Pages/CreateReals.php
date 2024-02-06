<?php

namespace App\Filament\Resources\RealsResource\Pages;

use App\Filament\Resources\RealsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReals extends CreateRecord
{
    protected static string $resource = RealsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
