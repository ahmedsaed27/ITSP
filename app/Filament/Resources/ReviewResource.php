<?php

namespace App\Filament\Resources;

use App\Enums\ApplicantStatus;
use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Apply;
use App\Models\InterviewDate;
use App\Models\Review;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    // protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    // protected static ?string $navigationGroup = 'Hr';

    protected static ?string $navigationGroup = 'Hiring Process';

    protected static ?int $navigationSort = 3;



    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apply.job.postion')->searchable(),
                TextColumn::make('apply.applicant.name')->searchable(),
                TextColumn::make('status')->formatStateUsing(function($state){
                    $value = match($state){
                        (int) ApplicantStatus::Acceptable->value => 'acceptable',
                        (int) ApplicantStatus::Rejected->value => 'rejected',
                        (int) ApplicantStatus::Priorities->value => 'priorities',
                    };
                    return $value;
                })->badge()->searchable(),
                TextColumn::make('note')->words(20)->wrap()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('interview')
                ->form([
                    Section::make()
                    ->schema([
                        TextInput::make('mail_to')->required(),
                        DateTimePicker::make('date'),
                        MarkdownEditor::make('task')->required()->columnSpan(2),
                    ])->columns(2)
                ])
                ->action(function(Model $record , $data){
                    $record->interview()->create([
                        'task' => $data['task'],
                        'mail_to' => $data['mail_to'],
                        'date' => $data['date']
                    ]);
                    
                    Notification::make()
                    ->success()
                    ->title('interview created successfuly')
                    ->send();
                }),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }


    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 2;
    }

}
