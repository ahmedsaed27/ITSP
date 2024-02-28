<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeReviewResource\Pages;
use App\Filament\Resources\EmployeeReviewResource\RelationManagers;
use App\Models\EmployeeReview;
use App\Models\Employees;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeReviewResource extends Resource
{
    protected static ?string $model = EmployeeReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup = 'Hr';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Select::make('employees_id')
                    ->label('employee')
                    ->options(User::all()->pluck('name' , 'id'))
                    ->required(),
                    TextInput::make('month')->required()->numeric(),

                    MarkdownEditor::make('review')->required()->columnSpan(2),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')->label('#')->searchable(),
                TextColumn::make('employee.name')->searchable(),
                TextColumn::make('review')->wrap()->words(20)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('month'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEmployeeReviews::route('/'),
            'create' => Pages\CreateEmployeeReview::route('/create'),
            'edit' => Pages\EditEmployeeReview::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 2;
    }
}
