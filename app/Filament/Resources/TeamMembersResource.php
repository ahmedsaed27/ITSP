<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TeamMembers;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeamMembersResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use App\Filament\Resources\TeamMembersResource\RelationManagers;

class TeamMembersResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = TeamMembers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Social';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                ->schema([
                    Section::make()
                    ->description('Add Team Members Detail')
                    ->schema([
                        TextInput::make('fullName')->required(),
                        TextInput::make('position')->required(),
                        MarkdownEditor::make('description')->required()->columnSpan(2),
                    ])->columns(2)

                ])->columnSpan(2),



                Group::make()
                ->schema([
                    Section::make('image')
                    ->schema([
                        FileUpload::make('image')
                        ->label('')
                        ->disk('public')
                        ->image()
                        ->required(),
                    ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fullName')->searchable()->sortable(),
                TextColumn::make('position')->searchable(),
                TextColumn::make('description')->label('description')
                ->words(20)
                ->wrap()
                ->toggleable(isToggledHiddenByDefault:true),
                ImageColumn::make('image')->label('image')->circular(),
                TextColumn::make('created_at')->dateTime(),

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
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMembers::route('/create'),
            'edit' => Pages\EditTeamMembers::route('/{record}/edit'),
        ];
    }



    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 3;
    }
}
