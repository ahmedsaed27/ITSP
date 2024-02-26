<?php

namespace App\Filament\Resources\ApplyResource\Pages;

use App\Filament\Resources\ApplyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ApplicantApplies extends ViewRecord
{
    protected static string $resource = ApplyResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var Post */
        $record = $this->getRecord();

        return $record->applicant->name;
    }
}
