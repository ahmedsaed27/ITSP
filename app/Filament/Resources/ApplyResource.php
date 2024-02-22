<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplyResource\Pages;
use App\Filament\Resources\ApplyResource\RelationManagers;
use App\Models\Applicant;
use App\Models\Apply;
use App\Models\Jobs;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

class ApplyResource extends Resource
{
    protected static ?string $model = Apply::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                ->schema([
                    Select::make('jobs_id')
                    ->label('Jobs')
                    ->options(Jobs::all()->pluck('postion' , 'id'))
                    ->required()
                    ->searchable(),

                    Select::make('applicant_id')
                    ->label('Applicant')
                    ->options(Applicant::all()->pluck('name' , 'id'))
                    ->required()
                    ->searchable(),


                    FileUpload::make('cv')->label('Cv')->disk('applicant')->acceptedFileTypes(['application/pdf']),

                    TextInput::make('years_experience')->numeric()->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('job.postion')->searchable(),
                TextColumn::make('applicant.name')->searchable(),
                TextColumn::make('years_experience')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                ActionGroup::make([
                    Action::make('cv')
                    ->action(function(Apply $app){
                        return response()->download(public_path('assets/applicant/'.$app->cv));
                    })
                    ->hidden(function(Apply $app){
                        return $app->cv ==  null;
                    })
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
            'index' => Pages\ListApplies::route('/'),
            'create' => Pages\CreateApply::route('/create'),
            'edit' => Pages\EditApply::route('/{record}/edit'),
        ];
    }
}
