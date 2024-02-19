<?php

namespace App\Filament\Hr\Resources\EmployeesResource\Pages;

use App\Filament\Hr\Resources\EmployeesResource;
use App\Models\Departments;
use App\Models\Skills;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;


class CreateEmployees extends CreateRecord
{
    use HasWizard;

    protected static string $resource = EmployeesResource::class;

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

    protected function afterCreate(): void
    {

        $emp = $this->record;

        $user = auth()->guard('hr')->user();

        Notification::make()
            ->title('Emp Created')
            ->icon('heroicon-o-plus')
            ->body("**{$user->name} create new {$emp->name}**")
            ->actions([
                Action::make('View')
                    ->url(EmployeesResource::getUrl('edit', ['record' => $emp])),
            ])
            ->sendToDatabase($user);
    }



    protected function getSteps(): array
    {
        return [
            Step::make('Personal Information')
                ->schema([
                    Section::make()->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('phone')->required()->numeric(),
                        Repeater::make('address')
                        ->simple(
                            TextInput::make('address')->required(),
                        )->required(),

                        Select::make('gander')
                        ->options([
                            0 => 'female',
                            1 => 'male'
                        ])
                        ->required()
                    ])->columns(),


                ]),


                Step::make('Education and Skills')
                ->schema([
                    Section::make()->schema([
                        MarkdownEditor::make('education')->required(),
                        TagsInput::make('skils')
                        ->suggestions(Skills::all()->pluck('title' , 'title')->flatten())
                        ->required()
                    ])->columns(),
                ]),

                Step::make('Work data')
                ->schema([
                    Section::make()->schema([
                        MarkdownEditor::make('position_type')->required(),
                        Select::make('departments_id')
                        ->label('department')
                        ->options(Departments::all()->pluck('name' , 'id'))
                        ->required()
                    ])->columns(),
                ]),

                Step::make('Email Info')
                ->schema([
                    Section::make()->schema([
                        TextInput::make('email')->email()->required(),
                        TextInput::make('password')->password()->revealable()->required()
                    ])->columns(),
                ]),

        ];
    }
}
