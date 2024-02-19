<?php

namespace App\Filament\Hr\Resources;

use App\Filament\Hr\Resources\LeaveRequestResource\Pages;
use App\Filament\Hr\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;



class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    DateRangePicker::make('date')->label('from - to')->required(),
                    MarkdownEditor::make('note'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->query(static::$model::where('id' , auth()->guard('hr')->id()))
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('employee.name')->searchable(),
                TextColumn::make('date'),
                TextColumn::make('note')->wrap()->words(20)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

                Action::make('accept')
                ->requiresConfirmation()
                ->action(function (LeaveRequest $record) {
                    $record->update([
                        'status' => 1
                    ]);
                }),

                Action::make('rejected')
                ->requiresConfirmation()
                ->action(function (LeaveRequest $record) {
                    $record->update([
                        'status' => 2
                    ]);
                }),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
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
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
