<?php

namespace App\Filament\Hr\Resources\EmployeeReviewResource\Pages;

use App\Filament\Hr\Resources\EmployeeReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeReviews extends ListRecords
{
    protected static string $resource = EmployeeReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
