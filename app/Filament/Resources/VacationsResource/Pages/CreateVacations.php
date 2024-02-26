<?php

namespace App\Filament\Resources\VacationsResource\Pages;

use App\Filament\Resources\VacationsResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

use function App\Helpers\calculation;

class CreateVacations extends CreateRecord
{
    protected static string $resource = VacationsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function handleRecordCreation(array $data): Model
    {
        $diffInDays = calculation($data['date']);
        $dayesCount = $diffInDays  > 0 ? $diffInDays : 1;


        $model = static::getModel()::where('user_id', $data['user_id'])->first();


        if ($model) {
            $model->update([
                'expire' => $model->expire + $dayesCount,
                'available' => ($model->available ?? 21) - $dayesCount,
            ]);
            
            $model->user->leave()->create([
                'date' => $data['date'],
                'note' => 'test',
                'status' => 1,
            ]);
        }

        return $model;
    }

}
