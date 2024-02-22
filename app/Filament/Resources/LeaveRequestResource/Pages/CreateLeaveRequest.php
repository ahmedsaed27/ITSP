<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $data['user_id'] = auth()->id();
        $data['status'] = 0;
        
        return $data;
    }
}
