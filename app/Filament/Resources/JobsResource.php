<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobsResource\Pages;
use App\Filament\Resources\JobsResource\RelationManagers;
use App\Models\Category;
use App\Models\Departments;
use App\Models\Jobs;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

class JobsResource extends Resource
{
    protected static ?string $model = Jobs::class;

    // protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    // protected static ?string $navigationGroup = 'Hr';
    protected static ?string $navigationGroup = 'Hiring Process';

    protected static ?int $navigationSort = 2;


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
                    ->schema([
                        Select::make('categories_id')
                        ->label('categorie')
                        ->options(Category::all()->pluck('name' , 'id'))
                        ->required(),

                        Select::make('departments_id')
                        ->label('department')
                        ->options(Departments::all()->pluck('name' , 'id'))
                        ->required(),
                    ]),
                    Section::make()
                    ->schema([
                        TextInput::make('postion')->required(),
                        Select::make('job_level')
                            ->options([
                                0 => 'junior',
                                1 => 'Senior',
                                3 => 'Mid level'
                            ])
                            ->searchable()
                            ->required(),

                        Select::make('job_type')
                            ->options([
                                0 => 'Full Time',
                                1 => 'Part Time',
                            ])
                            ->searchable()
                            ->required(),


                        Select::make('job_place')
                            ->options([
                                0 => 'remotly',
                                1 => 'on site',
                                3 => 'Hybrid'
                            ])
                            ->searchable()
                            ->required(),

                        TextInput::make('range_salary')->required(),

                        TagsInput::make('skills')
                        ->splitKeys(['Tab', ' ']),


                        MarkdownEditor::make('discription')->required()->columnSpan(2),
                        MarkdownEditor::make('requirments')->required()->columnSpan(2)

                    ])->columns(2)
                ])
                ->columnSpan(2),

                Group::make()
                ->schema([
                    FileUpload::make('image')->disk('jobs')->image()->required(),
                ])
                ->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('postion')->searchable(),
                TextColumn::make('job_level')->formatStateUsing(function(string $state){
                    return $state == 0 ? 'jonuir' : ($state == 1 ? 'Senior' : 'Mid level');
                })->searchable()->badge(),

                TextColumn::make('job_type')->formatStateUsing(function(string $state){
                    return $state == 0 ? 'Full Time' : 'Part Time';
                })->searchable()->badge(),

                TextColumn::make('job_place')->formatStateUsing(function(string $state){
                    return $state == 0 ? 'remotly' : ($state == 1 ? 'on site' : 'Hybrid');
                })->searchable()->badge(),

                TextColumn::make('categoty.name')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('range_salary')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('skills')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discription')->words(20)->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('requirments')->words(20)->wrap()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                ActionGroup::make([
                    Action::make('image')
                        ->action(function(Jobs $job){
                            return response()->download(public_path('assets/jobs/'.$job->image));
                        }),
                ]),
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJobs::route('/create'),
            'edit' => Pages\EditJobs::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 2;
    }
}
