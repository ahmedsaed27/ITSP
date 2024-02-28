<?php

namespace App\Filament\Resources\UsersResource\Pages;

use App\Filament\Resources\UsersResource;
use App\Models\Departments;
use App\Models\Employees;
use App\Models\Skills;
use Closure;
use Exception;
use Filament\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class EditUsers extends EditRecord
{
    use HasWizard;


    protected static string $resource = UsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            DB::beginTransaction();


            $userSystemInfo = !isNull($data['password'])
                ? collect($data)->only(['name', 'email', 'password', 'type'])->all()
                : collect($data)->only(['name', 'email', 'type'])->all();

            $userInfo = collect($data)->except(['name', 'email', 'password', 'type'])->all();

            $record->update($userSystemInfo);

            $employee = new Employees($userInfo);

            $record->employee()->update($employee->toArray());

            DB::commit();

            return $record;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Update user error from user number ' . auth()->id());
            Log::error($e->getMessage());

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('An error occurred. Contact the developers')
                ->send();
        }
    }


    protected function getSteps(): array
    {
        return [

            Step::make('Email Info')
                ->schema([
                    Group::make()->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->unique(table:'users' , column:'email' , ignorable: $this->record)->email()->required(),

                        Select::make('type')
                        ->label('roles')
                        ->options([
                            1 => 'Employee',
                            2 => 'Hr',
                            3 => 'Modirator',
                        ])
                        ->rules([
                            function () {
                                return function (string $attribute, $value, Closure $fail) {
                                    if (!in_array($value , [1 , 2 , 3])) {
                                        $fail('The :attribute is invalid.');
                                    }
                                };
                            },
                        ])
                        ->searchable()
                        ->required()
                        ->columnSpan(2),


                        TextInput::make('password')->confirmed()->password()->revealable(),
                        TextInput::make('password_confirmation')->password(),
                    ])->columns(),
            ]),

            Step::make('Personal Information')
                ->schema([
                    Group::make()
                    ->relationship('employee')
                    ->schema([

                        TextInput::make('phone')
                        ->required()
                        ->numeric(),

                        Select::make('gander')
                        ->options([
                            0 => 'female',
                            1 => 'male'
                        ])
                        ->required(),


                        TextInput::make('address')->required()->columnSpan(2)
                    ])->columns(),


                ]),

                Step::make('Education and Skills')
                ->schema([
                    Group::make()
                    ->relationship('employee')
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
                    Group::make()
                    ->relationship('employee')
                    ->schema([
                        MarkdownEditor::make('position_type')->required()->columnSpan(2),
                        Select::make('departments_id')
                        ->label('department')
                        ->options(Departments::all()->pluck('name' , 'id'))
                        ->searchable()
                        ->required()
                    ])->columns(),
                ]),
        ];
    }
}
