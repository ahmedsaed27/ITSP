<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use Carbon\Carbon;
use Closure;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

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

                    Select::make('status')
                    ->options([
                        0 => 'waiting',
                        1 => 'acceptable',
                        2 => 'rejected',

                    ])
                    ->default('0')
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
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('note')->wrap()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
