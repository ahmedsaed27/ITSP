<?php

namespace App\Filament\Resources;

use Closure;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Vacations;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\VacationsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\VacationsResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;


class VacationsResource extends Resource
{
    protected static ?string $model = Vacations::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

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
                       Select::make('user_id')
                            ->options(User::all()->pluck('name' , 'id'))
                            ->searchable()
                            ->required(),

                            DateRangePicker::make('date')->label('from - to')->required()->rules([
                                function (Get $get) {
                                    return function (string $attribute, $value, Closure $fail) use($get) {

                                        list($startDate, $endDate) = explode(' - ', $value);

                                        $startDate = Carbon::createFromFormat('d/m/Y', $startDate);
                                        $endDate = Carbon::createFromFormat('d/m/Y', $endDate);
                                        $diffInDays = $startDate->diffInDays($endDate);
                                        $dayesCount = $diffInDays > 0 ? $diffInDays : 1;

                                        $user = User::find($get('user_id'));

                                        if($user == null){
                                           return $fail('You must choose an employee');
                                        }


                                        if (Carbon::now()->startOfDay()->equalTo($startDate->startOfDay()) || !Carbon::now()->greaterThan($startDate)) {
                                            return;
                                        }else {
                                            $fail('The :attribute is invalid.');
                                        }


                                        if ($dayesCount > $user->vacations->available) {
                                            $fail('Your available vacations are only ' . $user->vacations->available . ' days');
                                        }
                                    };
                                },
                            ]),

                        MarkdownEditor::make('note')->columnSpan(2),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('total'),
                TextColumn::make('expire'),
                TextColumn::make('available'),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
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
            'index' => Pages\ListVacations::route('/'),
            'create' => Pages\CreateVacations::route('/create'),
            'edit' => Pages\EditVacations::route('/{record}/edit'),
        ];
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
