<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMembersResource\Pages;
use App\Filament\Resources\TeamMembersResource\RelationManagers;
use App\Models\TeamMembers;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamMembersResource extends Resource
{
    protected static ?string $model = TeamMembers::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
}