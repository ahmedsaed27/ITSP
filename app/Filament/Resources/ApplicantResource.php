<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicantResource\Pages;
use App\Filament\Resources\ApplicantResource\RelationManagers;
use App\Models\Applicant;
use App\Models\Citys;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
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


class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('phone')->required(),
                                TextInput::make('email')->required(),
                                DatePicker::make('birthYear')->format('d/m/Y'),
                                Select::make('citys_id')
                                ->options(Citys::all()->pluck('city' , 'id'))
                                ->searchable()
                                ->required(),
                                TextInput::make('area')->required(),
                                Select::make('gender')
                                ->options([
                                    0 => 'female',
                                    1 => 'male'
                                ])
                                ->required()
                                ->columnSpan(2),
                                TextInput::make('password')->revealable()->confirmed()->password()->required(),
                                TextInput::make('password_confirmation')->password()->required(),
                            ])->columns(2)
                    ])->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                FileUpload::make('cv')->disk('applicant')->acceptedFileTypes(['application/pdf'])->required(),
                            ]),

                        Section::make()
                            ->schema([
                                FileUpload::make('images')->disk('applicant')->image()->required(),
                            ]),
                    ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->copyable()->searchable()->badge(),
                TextColumn::make('phone')->copyable()->searchable()->badge(),
                TextColumn::make('city.city')->searchable(),
                TextColumn::make('birthYear'),
                TextColumn::make('area')->words(20)->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gender')->formatStateUsing(fn (string $state): string => $state == 0 ? 'female' : 'male')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),


                Action::make('Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function(Applicant $app){
                    unlink(public_path('assets/applicant/'.$app->images));
                    unlink(public_path('assets/applicant/'.$app->cv));
                    return $app->delete();
                }),


                ActionGroup::make([
                    Action::make('image')
                        ->action(function(Applicant $app){
                            return response()->download(public_path('assets/applicant/'.$app->images));
                        }),

                    Action::make('cv')
                        ->action(function(Applicant $app){
                            return response()->download(public_path('assets/applicant/'.$app->cv));
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
            'index' => Pages\ListApplicants::route('/'),
            'create' => Pages\CreateApplicant::route('/create'),
            'edit' => Pages\EditApplicant::route('/{record}/edit'),
        ];
    }
}
