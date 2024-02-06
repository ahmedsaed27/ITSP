<?php

namespace App\Filament\Resources\TeamMembersResource\Pages;

use App\Filament\Resources\TeamMembersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamMembers extends CreateRecord
{
    protected static string $resource = TeamMembersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
