<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use App\Models\Departments;
use App\Models\Employees;
use App\Models\Skills;
use App\Models\Vacations;
use Closure;
use Exception;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateUsers extends CreateRecord
{
    use HasWizard;

    protected static string $resource = UsersResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }


    protected function handleRecordCreation(array $data): Model
    {

        try{

            DB::beginTransaction();
            $userSystemInfo = collect($data)->only(['name', 'email' , 'password' , 'type'])->all();
            $userInfo = collect($data)->except(['name', 'email' , 'password' , 'type'])->all();

            $user =  static::getModel()::create($userSystemInfo);


            $employee = new Employees($userInfo);
            $user->employee()->save($employee);

            // $user->vacations()->create([
            //     'total' => 21,
            //     'expire' => 0,
            //     'available' => 21,
            // ]);

            DB::commit();

            return $user;
        }catch(Exception $e){

            DB::rollBack();

            Log::error('create user error from user number' . auth()->id());
            Log::error($e->getMessage());

            Notification::make()
            ->danger()
            ->title('Error')
            ->body('An error occurred. Contact the developers')
            ->send();

        }

    }

    protected function afterCreate(): void
    {

        $emp = $this->record;

        $user = auth()->user();

        Notification::make()
            ->title('Emp Created')
            ->icon('heroicon-o-plus')
            ->body("**{$user->name} create new user called {$emp->name}**")
            ->actions([
                Action::make('View')
                    ->url(UsersResource::getUrl('edit', ['record' => $emp])),
            ])
            ->sendToDatabase($user);
    }



    protected function getSteps(): array
    {
        return [

            Step::make('Email Info')
                ->schema([
                    Section::make()->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->unique(table:'users' , column:'email')->email()->required(),

                        Select::make('type')
                        ->label('roles')
                        ->options([
                            1 => 'employee',
                            2 => 'Hr',
                            3 => 'Modirator',
                        ])
                        ->searchable()
                        ->required()
                        ->rules([
                            function () {
                                return function (string $attribute, $value, Closure $fail) {
                                    if (!in_array($value , [1 , 2 , 3])) {
                                        $fail('The :attribute is invalid.');
                                    }
                                };
                            },
                        ])
                        ->columnSpan(2),

                        TextInput::make('password')->confirmed()->password()->revealable()->required(),
                        TextInput::make('password_confirmation')->password(),

                    ])->columns(),
                ]),

            Step::make('Personal Information')
                ->schema([
                    Section::make()
                    ->schema([
                        TextInput::make('phone')->required()->numeric(),
                        Select::make('gander')
                        ->options([
                            0 => 'female',
                            1 => 'male'
                        ])
                        ->required(),

                        MarkdownEditor::make('address')->required()->columnSpan(2),
                    ])->columns(),


                ]),


                Step::make('Education and Skills')
                ->schema([
                    Section::make()
                    ->schema([
                        TextInput::make('college')->required(),
                        TextInput::make('university')->required(),
                        TextInput::make('Specialization')->required(),
                        TagsInput::make('skils')
                        ->suggestions(Skills::all()->pluck('title' , 'title')->flatten())
                        ->required()
                    ])->columns(),
                ]),

                Step::make('Work data')
                ->schema([
                    Section::make()
                    ->schema([
                        MarkdownEditor::make('position_type')->required(),
                        Select::make('departments_id')
                        ->label('department')
                        ->options(Departments::all()->pluck('name' , 'id'))
                        ->searchable()
                        ->required()
                    ])->columns(),
                ])

        ];
    }
}
