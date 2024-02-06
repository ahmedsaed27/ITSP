<?php

namespace App\Filament\Resources\TeamMembersResource\Pages;

use App\Filament\Resources\TeamMembersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeamMembers extends EditRecord
{
    protected static string $resource = TeamMembersResource::class;

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
