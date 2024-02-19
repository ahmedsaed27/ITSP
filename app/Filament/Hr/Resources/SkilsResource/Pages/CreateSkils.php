<?php

namespace App\Filament\Hr\Resources\SkilsResource\Pages;

use App\Filament\Hr\Resources\SkilsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSkils extends CreateRecord
{
    protected static string $resource = SkilsResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
