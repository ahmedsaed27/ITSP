<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewUsers extends ViewRecord
{
    protected static string $resource = UsersResource::class;

    // protected static string $view = 'filament.resources.users.pages.view-users';

    public function getTitle(): string | Htmlable
    {
        /** @var Post */
        $record = $this->getRecord();

        return $record->name;
    }

}
