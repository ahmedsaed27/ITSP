<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealsResource\Pages;
use App\Filament\Resources\RealsResource\RelationManagers;
use App\Models\Category;
use App\Models\Reals;
use App\Models\TeamMembers;
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

class RealsResource extends Resource
{
    protected static ?string $model = Reals::class;

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
                    TextInput::make('vedioLink')->label('vedio link')->required(),

                    Select::make('category_id')
                        ->label('category')
                        ->relationship(name: 'category', titleAttribute: 'name')
                        ->createOptionForm([
                            Section::make()
                                ->schema([
                                    TextInput::make('name')->required()->columnSpan(2),
                                    FileUpload::make('image')->required()->image()->columnSpan(2),
                                    MarkdownEditor::make('description')->required()->columnSpan(2),
                                ])->columns(2)

                        ])
                        ->options(Category::all()->pluck('name', 'id')),

                    Select::make('team_members_id')
                        ->label('team members')
                        ->relationship(name: 'member', titleAttribute: 'fullName')
                        ->createOptionForm([
                            Section::make()
                                ->schema([
                                    TextInput::make('fullName')->required()->columnSpan(2),
                                    TextInput::make('position')->required()->columnSpan(2),
                                    FileUpload::make('image')->required()->image()->columnSpan(2),
                                    MarkdownEditor::make('description')->required()->columnSpan(2),
                                ])->columns(2)

                        ])
                        ->options(TeamMembers::all()->pluck('fullName', 'id')),

                        MarkdownEditor::make('description')->required()->columnSpan(2),

                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.fullName')->searchable()->sortable(),
                TextColumn::make('category.name')->searchable()->sortable()->badge(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('vedioLink')
                ->color('warning')
                ->icon('heroicon-o-clipboard-document')
                ->copyable()
                ->copyMessage('link copied successful')
                ->searchable()
                ->badge()
                ->wrap(),
                TextColumn::make('description')->label('description')
                ->words(10)
                ->toggleable(isToggledHiddenByDefault:true),
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
            'index' => Pages\ListReals::route('/'),
            'create' => Pages\CreateReals::route('/create'),
            'edit' => Pages\EditReals::route('/{record}/edit'),
        ];
    }


    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 3;
    }
}
