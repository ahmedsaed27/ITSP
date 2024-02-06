<?php

namespace App\Filament\Resources\TeamMembersResource\Pages;

use App\Filament\Exports\TeamMembersExporter;
use App\Filament\Resources\TeamMembersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamMembers extends ListRecords
{
    protected static string $resource = TeamMembersResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }

    protected function getActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(TeamMembersExporter::class)
                ->color('primary'),
            Actions\CreateAction::make(),
        ];
    }
}
