<?php

namespace App\Filament\Hr\Resources\EmployeeReviewResource\Pages;

use App\Filament\Hr\Resources\EmployeeReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeReview extends EditRecord
{
    protected static string $resource = EmployeeReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
