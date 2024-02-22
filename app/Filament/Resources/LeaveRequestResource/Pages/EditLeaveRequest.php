<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use App\Models\Vacations;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

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
