<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicesResource\Pages;
use App\Filament\Resources\ServicesResource\RelationManagers;
use App\Models\Category;
use App\Models\Services;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServicesResource extends Resource
{
    protected static ?string $model = Services::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Social';

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
                    TextInput::make('title')->required(),
                    TextInput::make('icon')->required(),
                    Select::make('category_id')->label('category_id')
                        ->relationship(name: 'category', titleAttribute: 'name')
                        ->createOptionForm([
                            Section::make()
                                ->schema([
                                    TextInput::make('name')->required()->columnSpan(2),
                                    FileUpload::make('image')->required()->image()->columnSpan(2),
                                    MarkdownEditor::make('description')->required()->columnSpan(2),
                                ])->columns(2)

                        ])
                        ->options(Category::all()->pluck('name', 'id'))
                        ->columnSpan(2),
                    MarkdownEditor::make('description')->required()->columnSpan(2)
                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('icon')->searchable()->sortable(),
                TextColumn::make('category.name')->label('category')->badge()->searchable(),
                TextColumn::make('description')->label('description')
                ->words(20)
                ->wrap()
                ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateServices::route('/create'),
            'edit' => Pages\EditServices::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 3;
    }
}
