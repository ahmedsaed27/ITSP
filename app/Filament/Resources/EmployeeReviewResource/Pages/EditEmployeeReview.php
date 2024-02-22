<?php

namespace App\Filament\Resources\EmployeeReviewResource\Pages;

use App\Filament\Resources\EmployeeReviewResource;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
