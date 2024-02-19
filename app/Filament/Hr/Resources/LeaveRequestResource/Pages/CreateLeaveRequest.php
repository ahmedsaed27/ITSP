<?php

namespace App\Filament\Hr\Resources\LeaveRequestResource\Pages;

use App\Filament\Hr\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['employees_id'] = auth()->guard('hr')->id();
        $data['status'] = 0;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
