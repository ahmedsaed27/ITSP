<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplyResource\Pages;
use App\Filament\Resources\ApplyResource\RelationManagers;
use App\Infolists\Components\ApplicantCv;
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


use Filament\Infolists;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group as InfolistGroup;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Illuminate\Database\Eloquent\Model;

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
                Tables\Actions\ViewAction::make(),

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make()
                    ->schema([
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    InfolistGroup::make([
                                        TextEntry::make('applicant.name')->label('name'),
                                        TextEntry::make('applicant.email')->label('email')->badge()->copyable()->icon('heroicon-m-envelope'),
                                        TextEntry::make('applicant.phone')->label('Phone')->copyable()->badge()->icon('heroicon-o-device-phone-mobile'),

                                    ]),
                                    InfolistGroup::make([
                                        TextEntry::make('job.postion')->label('Postion')->copyable()->badge()->icon('heroicon-o-device-phone-mobile'),
                                        TextEntry::make('job.job_level')->formatStateUsing(fn (string $state): string => $state == 0 ? 'female' : 'male')->label('Gander')->badge(),
                                        TextEntry::make('job.job_type')->label('Job Type')->formatStateUsing(function(string $state){
                                            return $state == 0 ? 'Full Time' : 'Part Time';
                                        })->badge()->copyable()->icon('heroicon-o-home-modern'),
                                        TextEntry::make('job.job_place')->label('Job Place')->badge()->copyable()->icon('heroicon-o-home-modern'),
                                        TextEntry::make('job.range_salary')->label('Range Salary')->badge()->copyable()->icon('heroicon-o-home-modern'),
                                        TextEntry::make('job.skills')->label('Skills')->badge()->copyable()->icon('heroicon-o-home-modern'),
                                    ]),
                                ]),
                                ImageEntry::make('applicant.images')
                                ->disk('applicant')
                                ->circular()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
                    Split::make([
                        InfolistSection::make('Job Information')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            InfolistGroup::make()
                            ->schema([
                                TextEntry::make('job.requirments')
                                ->label('requirments')
                                ->listWithLineBreaks()
                                ->bulleted()
                                ->copyable(),
                            ]),

                            InfolistGroup::make()
                            ->schema([
                                TextEntry::make('job.discription')
                                ->label('discription')
                                ->listWithLineBreaks()
                                ->bulleted()
                                ->copyable(),
                            ])

                        ])

                        ->columns(2),
                        InfolistSection::make('Dates')
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
                    ])->columnSpan(2),
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
            'view' => Pages\ApplicantApplies::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        $userType = auth()->user()->type;

        return $userType == 0 || $userType == 2;
    }
}
