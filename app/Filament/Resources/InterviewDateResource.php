<?php

namespace App\Filament\Resources;

use App\Enums\ApplicantStatus;
use App\Filament\Resources\InterviewDateResource\Pages;
use App\Filament\Resources\InterviewDateResource\RelationManagers;
use App\Models\FinalInterviewStatus;
use App\Models\InterviewDate;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InterviewDateResource extends Resource
{
    protected static ?string $model = InterviewDate::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Hiring Process';

    protected static ?int $navigationSort = 4;


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
                TextColumn::make('review.apply.applicant.name')->searchable(),
                TextColumn::make('task'),
                TextColumn::make('mail_to'),
                TextColumn::make('date')->dateTime(),
                ToggleColumn::make('attend'),
                 
                // TextColumn::make('attend')
                // ->formatStateUsing(fn (string $state): string => $state == 0 ? 'absence' : 'attend')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Final Status')
                ->form([
                    // dd(ApplicantStatus::Acceptable),
                    Select::make('status')
                    ->options([
                        ApplicantStatus::Acceptable->value => ApplicantStatus::Acceptable->name,
                        ApplicantStatus::Rejected->value => ApplicantStatus::Rejected->name,
                    ])
                    ->required()
                    ->searchable(),

                    // DateTimePicker::make('date')->required(),

                    MarkdownEditor::make('notes')->required(),

                    MarkdownEditor::make('message')->required(),

                ])
                ->action(function(Model $record , $data){
                    FinalInterviewStatus::create([
                        'interview_date_id' => $record->id,
                        'status' => $data['status'],
                        'date' => $record->date,
                        'notes' => $data['notes'],
                        'message' => $data['message'],
                    ]);

                    Notification::make()
                    ->success()
                    ->title('created success')
                    ->send();
                }),
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
            'index' => Pages\ListInterviewDates::route('/'),
            'create' => Pages\CreateInterviewDate::route('/create'),
            'edit' => Pages\EditInterviewDate::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static  function canCreate(): bool
    {
        return false;
    } 
}
