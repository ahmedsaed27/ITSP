<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Closure;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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

    protected static ?string $navigationIcon = 'heroicon-o-arrow-left-end-on-rectangle';

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
                    DateRangePicker::make('date')->label('from - to')->required()->rules([
                        function () {
                            return function (string $attribute, $value, Closure $fail) {

                                list($startDate, $endDate) = explode(' - ', $value);

                                $startDate = Carbon::createFromFormat('d/m/Y', $startDate);
                                $endDate = Carbon::createFromFormat('d/m/Y', $endDate);
                                $diffInDays = $startDate->diffInDays($endDate);
                                $dayesCount = $diffInDays > 0 ? $diffInDays : 1;

                                if (Carbon::now()->startOfDay()->equalTo($startDate->startOfDay()) || !Carbon::now()->greaterThan($startDate)) {
                                    return;
                                }else {
                                    $fail('The :attribute is invalid.');
                                }

                                if(auth()->user()->vacations == null){
                                    return;
                                }


                                if($dayesCount > auth()->user()->vacations->available){
                                    $fail('Your available vacations are only '.auth()->user()->vacations->available.' days');
                                }
                            };
                        },
                    ]),

                    MarkdownEditor::make('note'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::$model::where('user_id' , auth()->id()))
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('date'),
                TextColumn::make('note')->wrap()->words(20)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('answer')->wrap()->words(20)->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'waiting' => 'warning',
                    'acceptable' => 'success',
                    'rejected' => 'danger',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

                // Action::make('accept')
                // ->requiresConfirmation()
                // ->action(function (LeaveRequest $record) {

                //     $vacation = $record->user->vacations;

                //     list($startDate, $endDate) = explode(' - ', $record->date);

                //     $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
                //     $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);
                //     $diffInDays = $carbonStartDate->diffInDays($carbonEndDate);

                //     if(!Carbon::now()->startOfDay()->equalTo($carbonStartDate->startOfDay()) && Carbon::now()->greaterThan($carbonStartDate)){
                //         return Notification::make()
                //             ->danger()
                //             ->title('invalid date')
                //             ->send();
                //     }

                //     if($vacation  !== null){
                //         if($diffInDays > $vacation->available){
                //             return Notification::make()
                //             ->danger()
                //             ->title('only have '.$vacation->available.' available')
                //             ->send();
                //         }
                //     }

                //     $record->update([
                //         'status' => 1
                //     ]);
                // })
                // ->icon('heroicon-o-check-badge')
                // ->color('success')
                // ->hidden(fn(LeaveRequest $record) => $record->status !== 'waiting'),


                // Action::make('rejected')
                // ->requiresConfirmation()
                // ->action(function (LeaveRequest $record) {
                //     $record->update([
                //         'status' => 2
                //     ]);
                // })
                // ->color('danger')
                // ->icon('heroicon-o-x-circle')
                // ->hidden(fn(LeaveRequest $record) => $record->status !== 'waiting'),




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

    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 1 || $userType == 3;
    }
}
