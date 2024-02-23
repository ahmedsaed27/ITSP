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
                    DateRangePicker::make('date')->label('from - to')->required()->rules([
                        function () {
                            return function (string $attribute, $value, Closure $fail) {

                                list($startDate, $endDate) = explode(' - ', $value);

                                $startDate = Carbon::createFromFormat('d/m/Y', $startDate);
                                $endDate = Carbon::createFromFormat('d/m/Y', $endDate);
                                $diffInDays = $startDate->diffInDays($endDate);
                                $dayesCount = $diffInDays > 0 ? $diffInDays : 1;


                                if ($dayesCount > 21) {
                                    return $fail('No more than 21 days of leave should be requested');
                                }

                                if (Carbon::now()->format('d/m/Y') > $startDate->format('d/m/Y')) {
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
            // ->query(static::$model::where('id' , auth()->guard('hr')->id()))
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('user.name')->searchable(),
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

                    $vacation = auth()->user()->vacations;

                    list($startDate, $endDate) = explode(' - ', $record->date);

                    $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
                    $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);
                    $diffInDays = $carbonStartDate->diffInDays($carbonEndDate);

                    if($diffInDays > $vacation->available){
                        $this->halt();
                    }

                    $record->update([
                        'status' => 1
                    ]);
                })
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->hidden(fn(LeaveRequest $record) => $record->status !== 'waiting'),


                Action::make('rejected')
                ->requiresConfirmation()
                ->action(function (LeaveRequest $record) {
                    $record->update([
                        'status' => 2
                    ]);
                })
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->hidden(fn(LeaveRequest $record) => $record->status !== 'waiting'),




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
