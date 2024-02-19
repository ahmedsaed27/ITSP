<?php

namespace App\Filament\Hr\Resources\EmployeeReviewResource\Pages;

use App\Filament\Hr\Resources\EmployeeReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeReview extends CreateRecord
{
    protected static string $resource = EmployeeReviewResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
