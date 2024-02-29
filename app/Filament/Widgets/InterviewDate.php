<?php

namespace App\Filament\Widgets;

use App\Models\InterviewDate as ModelsInterviewDate;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class InterviewDate extends BaseWidget
{
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsInterviewDate::query()
            )
            ->columns([
                TextColumn::make('review.apply.applicant.name')->searchable(),
                TextColumn::make('task'),
                TextColumn::make('mail_to'),
                TextColumn::make('attend')
                ->formatStateUsing(fn (string $state): string => $state == 0 ? 'absence' : 'attend')->badge(),
            ]);
    }
}
