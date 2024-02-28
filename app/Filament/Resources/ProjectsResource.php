<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectsResource\Pages;
use App\Filament\Resources\ProjectsResource\RelationManagers;
use App\Models\Category;
use App\Models\Projects;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class ProjectsResource extends Resource
{
    protected static ?string $model = Projects::class;

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
                Group::make()
                ->schema([
                    Section::make()
                    ->description('Add your Projects Detail.')
                    ->schema([
                        TextInput::make('title')->required(),
                        TextInput::make('link')->required(),
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
                        MarkdownEditor::make('description')->required()->columnSpan(2),
                    ])->columns(2)
                ])->columnSpan(2),

                Group::make()->schema([
                    Section::make()
                    ->description('you can add more then one image')
                    ->schema([
                        FileUpload::make('images')->image()->required()->multiple(),
                    ])
                ])->columnSpan(1)

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('title')->searchable(),
                TextColumn::make('link')->label('link')
                ->color('warning')
                ->icon('heroicon-o-clipboard-document')
                ->copyable()
                ->copyMessage('link copied successful')
                ->searchable()
                ->badge(),
                TextColumn::make('category.name')->label('category')->badge()->searchable(),
                TextColumn::make('description')->label('description')
                ->words(20)
                ->wrap()
                ->toggleable(isToggledHiddenByDefault:true),
                ImageColumn::make('images')->label('images')->circular()->stacked()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                QueryBuilder::make()
                ->constraints([
                    TextConstraint::make('title'),
                    TextConstraint::make('link'),
                    DateConstraint::make('created_at'),
                    RelationshipConstraint::make('category')
                    ->selectable(
                        IsRelatedToOperator::make()
                            ->titleAttribute('name'),
                    ),
                    TextConstraint::make('description'),
                ])->constraintPickerColumns(2)
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProjects::route('/create'),
            'edit' => Pages\EditProjects::route('/{record}/edit'),
        ];
    }


    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 3;
    }
}
