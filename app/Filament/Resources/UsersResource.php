<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Employee';

    protected static ?string $pluralModelLabel = 'Employees';

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $slug = 'employees';

    protected static ?string $navigationGroup = 'Hr';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;


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
            // ->query(User::query()->where('type' , '!=' , '0'))
            ->columns([
                TextColumn::make('id')->label('#')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->copyable()->searchable()->badge(),
                TextColumn::make('type')->copyable()
                ->searchable()
                ->formatStateUsing(function(string $state){
                    $value = match ($state) {
                        '0' => 'Admin',
                        '1' => 'Employee',
                        '2' => 'Hr',
                        '3' => 'Modirator'
                    };

                    return $value;
                })
                ->badge(),
                TextColumn::make('employee.phone')->label('phone')->copyable()->badge()->searchable(),
                TextColumn::make('employee.address')->label('address')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('employee.gander')->label('gander')->toggleable(isToggledHiddenByDefault: true)->formatStateUsing(fn (string $state): string => $state == 0 ? 'female' : 'male'),
                TextColumn::make('employee.college')->label('college')->toggleable(isToggledHiddenByDefault: true)->wrap()->words(20),
                TextColumn::make('employee.university')->label('university')->toggleable(isToggledHiddenByDefault: true)->wrap()->words(20),
                TextColumn::make('employee.Specialization')->label('Specialization')->toggleable(isToggledHiddenByDefault: true)->wrap()->words(20),
                TextColumn::make('employee.skils')->label('skils')->toggleable(isToggledHiddenByDefault: true)->badge()->wrap(),
                TextColumn::make('employee.created_at')->label('Created At')->toggleable(isToggledHiddenByDefault: false)->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('activities')->url(fn ($record) => UsersResource::getUrl('activities', ['record' => $record])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewUsers::class,
            Pages\EditUsers::class,
            Pages\UserVacations::class
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Group::make([
                                        TextEntry::make('name'),
                                        TextEntry::make('email')->badge()->copyable()->icon('heroicon-m-envelope'),
                                        TextEntry::make('type')
                                        ->formatStateUsing(function(string $state){
                                                $value = match ($state) {
                                                    '0' => 'Admin',
                                                    '1' => 'Employee',
                                                    '2' => 'Hr',
                                                    '3' => 'developer'
                                                };

                                                return $value;
                                            })
                                            ->badge(),
                                    ]),
                                    Group::make([
                                        TextEntry::make('employee.phone')->label('Phone')->copyable()->badge()->icon('heroicon-o-device-phone-mobile'),
                                        TextEntry::make('employee.gander')->formatStateUsing(fn (string $state): string => $state == 0 ? 'female' : 'male')->label('Gander')->badge(),
                                        TextEntry::make('employee.address')->label('Address')->badge()->copyable()->icon('heroicon-o-home-modern'),
                                    ]),
                                ]),
                                ImageEntry::make('image')
                                ->defaultImageUrl(asset('assets/employee/confident-cheerful-young-businesswoman_1262-20881.avif'))
                                ->hiddenLabel()
                                ->circular()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
                    Split::make([
                        Section::make('About')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            TextEntry::make('employee.skils')
                                ->label('Skills')
                                ->badge(),
                            TextEntry::make('employee.Specialization')
                                ->icon('heroicon-o-briefcase')
                                ->label('Specialization'),

                            TextEntry::make('employee.college')
                                ->icon('heroicon-o-academic-cap')
                                ->label('College'),

                            TextEntry::make('employee.university')
                                ->icon('heroicon-o-building-library')
                                ->label('University'),
                        ])
                        ->columns(2),
                        Section::make('Dates')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Created At')
                                ->icon('heroicon-o-calendar-days')
                                ->dateTime(),

                                TextEntry::make('updated_at')
                                ->label('Updated At')
                                ->icon('heroicon-o-calendar-days')
                                ->dateTime(),

                        ])
                        ->grow(false),
                    ])->columnSpan(2)
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
            'view' => Pages\ViewUsers::route('/{record}'),
            'vacations' => Pages\UserVacations::route('/{record}/comments'),
            'activities' => Pages\ListUserActivities::route('/{record}/activities'),

        ];
    }

    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 2;
    }
}
