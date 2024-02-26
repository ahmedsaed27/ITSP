<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use App\Filament\Resources\UsersResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;


class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {

        $emp = $this->record;

        $users = User::whereIn('type' , [0 , 2 , 3])->get();

        Notification::make()
            ->title('Leave Request')
            ->icon('heroicon-o-arrow-left-start-on-rectangle')
            ->body("**{$emp->user->name} create new Leave Request **")
            ->actions([
                Action::make('View')
                    ->url(UsersResource::getUrl('vacations', ['record' => $emp->user_id])),
            ])
            ->sendToDatabase($users);
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = auth()->id();
        $data['status'] = 0;

        return $data;
    }
}
