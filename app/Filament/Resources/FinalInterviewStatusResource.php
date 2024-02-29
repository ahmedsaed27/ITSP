<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinalInterviewStatusResource\Pages;
use App\Filament\Resources\FinalInterviewStatusResource\RelationManagers;
use App\Models\FinalInterviewStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinalInterviewStatusResource extends Resource
{
    protected static ?string $model = FinalInterviewStatus::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Hiring Process';

    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('interview.review.apply.applicant.name')->searchable(),
                TextColumn::make('date')->dateTime(),
                TextColumn::make('notes')->wrap()->words(20)->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('message')->wrap()->words(20)->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('status')
                ->formatStateUsing(fn (string $state): string => $state == 0 ? 'Rejected' : 'Accepted')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinalInterviewStatuses::route('/'),
            'create' => Pages\CreateFinalInterviewStatus::route('/create'),
            'edit' => Pages\EditFinalInterviewStatus::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;  
    }
}
