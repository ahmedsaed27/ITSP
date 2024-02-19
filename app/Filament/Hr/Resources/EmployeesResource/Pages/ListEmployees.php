<?php

namespace App\Filament\Hr\Resources\EmployeesResource\Pages;

use App\Filament\Exports\EmployeesExporter;
use App\Filament\Hr\Resources\EmployeesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
