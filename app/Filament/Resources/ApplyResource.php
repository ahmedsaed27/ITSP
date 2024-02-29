<?php

namespace App\Filament\Resources;

use App\Enums\ApplicantStatus;
use App\Filament\Resources\ApplyResource\Pages;
use App\Filament\Resources\ApplyResource\RelationManagers;
use App\Infolists\Components\ApplicantCv;
use App\Models\Applicant;
use App\Models\Apply;
use App\Models\Jobs;
use App\Models\Review;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
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

    // protected static ?string $navigationIcon = 'heroicon-o-plus';

    // protected static ?string $navigationGroup = 'Hr';
    protected static ?string $navigationGroup = 'Hiring Process';

    protected static ?int $navigationSort = 3;

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

                    Select::make('applicant_id')
                    ->label('Applicant')
                    ->options(Applicant::all()->pluck('name' , 'id'))
                    ->required()
                    ->searchable(),

                    Select::make('jobs_id')
                    ->label('Jobs')
                    ->options(Jobs::all()->pluck('postion' , 'id'))
                    ->required()
                    ->searchable(),
                    // ->rules([
                    //     function (Get $get) {
                    //         return function (string $attribute, $value, Closure $fail) use($get){
                    //             $ApplicantApplyes = Applicant::find($get('applicant_id'))->applies;
                    //             if($ApplicantApplyes->contains('jobs_id', $value)){
                    //                 $fail('You cannot apply for a job more than once');
                    //             }
                    //         };
                    //     },
                    // ]),

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
                TextColumn::make('status')->formatStateUsing(function($state){
                    $value = match($state){
                        (int) ApplicantStatus::New->value => 'new',
                        (int) ApplicantStatus::Acceptable->value => 'acceptable',
                        (int) ApplicantStatus::Rejected->value => 'rejected',
                        (int) ApplicantStatus::Priorities->value => 'priorities',
                    };
                    return $value;
                })->badge()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Action::make('status')
                ->form([
                    Select::make('status')
                    ->options([
                        2 => 'Acceptable',
                        3 => 'Rejected',
                        4 => 'Priorities',

                    ])
                    ->searchable()
                    ->required()
                ])->action(function(Model $record , $data){
                    $record->update([
                        'status' => $data['status']
                    ]);

                    $record->review()->create([
                        'status' => $data['status'],
                        'note' => 'test',
                    ]);
                   
                     
                })->hidden(function(Model $record){
                    return $record->review !== null || $record->status !== 0;
                }),
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
