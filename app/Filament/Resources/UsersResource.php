<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Filament\Resources\UsersResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        '3' => 'developer'
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}
