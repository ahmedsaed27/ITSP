<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use App\Filament\Widgets\StatsOverview;
use Carbon\Carbon;
use Closure;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class UserVacations extends ManageRelatedRecords
{
    protected static string $resource = UsersResource::class;

    protected static string $relationship = 'leave';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public function getTitle(): string | Htmlable
    {
        return $this->record->name . ' Leave';
    }


    public static function getNavigationLabel(): string
    {
        return 'Leave';
    }

    public function getBreadcrumb(): string
    {
        return 'Leave';
    }



    public function form(Form $form): Form
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

                                if (Carbon::now()->startOfDay()->equalTo($startDate->startOfDay()) || !Carbon::now()->greaterThan($startDate)) {
                                    return;
                                }else {
                                    $fail('The :attribute is invalid.');
                                }

                                if($this->record->vacations == null){
                                    return;
                                }


                                if($dayesCount > $this->record->vacations->available){
                                    $fail('Your available vacations are only '.$this->record->vacations->available.' days');
                                }
                            };
                        },
                    ]),

                    Select::make('status')
                    ->options([
                        (int) 0 => 'waiting',
                        (int) 1 => 'acceptable',
                        (int) 2 => 'rejected',
                    ])
                    ->default(0)
                    ->searchable()
                    ->required(),

                    MarkdownEditor::make('note'),
                ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'waiting' => 'warning',
                    'acceptable' => 'success',
                    'rejected' => 'danger',
                }),
                Tables\Columns\TextColumn::make('note')->wrap()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('answer')->wrap()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

                Action::make('accept')
                ->requiresConfirmation()
                ->action(function (Model $record) {

                    $vacation = $record->user->vacations;

                    list($startDate, $endDate) = explode(' - ', $record->date);

                    $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
                    $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);
                    $diffInDays = $carbonStartDate->diffInDays($carbonEndDate);

                    if(!Carbon::now()->startOfDay()->equalTo($carbonStartDate->startOfDay()) && Carbon::now()->greaterThan($carbonStartDate)){
                        return Notification::make()
                            ->danger()
                            ->title('invalid date')
                            ->send();
                    }

                    if($vacation  !== null){
                        if($diffInDays > $vacation->available){
                            return Notification::make()
                            ->danger()
                            ->title('only have '.$vacation->available.' available')
                            ->send();
                        }
                    }

                    $record->update([
                        'status' => 1
                    ]);
                })
                ->icon('heroicon-o-check-badge')
                ->color('primary')
                ->hidden(fn(Model $record) => $record->status !== 'waiting'),


                Action::make('rejected')
                ->form([
                    MarkdownEditor::make('answer')->required()
                ])
                ->requiresConfirmation()
                ->action(function (Model $record , array $data) {
                    $record->update([
                        'status' => 2,
                        'answer' => $data['answer'],
                    ]);
                })
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->hidden(fn(Model $record) => $record->status !== 'waiting'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::make([
                'total' => $this->record
            ])
        ];
    }
}
