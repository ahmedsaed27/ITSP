<?php

namespace App\Filament\Widgets;

use App\Enums\Roles;
use App\Enums\Status;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction as ActionsDeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction as ActionsEditAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Widgets\Concerns\InteractsWithEvents;


class CalendarWidget extends FullCalendarWidget
{


    protected static bool $isLazy = false;

    // protected int | string | array $columnSpan = [
    //     'md' => 1,
    //     'xl' => 1,
    // ];

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'start' => $arguments['start'] ?? null,
                            'end' => $arguments['end'] ?? null
                        ]);
                    }
                )
        ];
    }


    public Model | string | null $model = Task::class;

    public function fetchEvents(array $fetchInfo): array
    {
        return Task::query()
            ->when(function($q){
                $user_role = auth()->user()->type;

                $q->where(function($q) use ($user_role) {
                    if ($user_role !== Roles::Admin->value) {
                        $q->where('user_id', auth()->id());
                    } else {
                        $q->orWhere('user_id','!=' ,auth()->id());
                    }
                });
            })
            ->where('start', '>=', $fetchInfo['start'])
            ->where('created_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Task $task) {
                return [
                    'id'    => $task->id,
                    'title' => $task->user->name,
                    'start' => $task->start,
                    'end'   => $task->end,
                ];
            })
            ->toArray();
    }

    protected function modalActions(): array
    {
        return [
            ActionsEditAction::make()

            ->mountUsing(
                function ($record,  $form, array $arguments) {
                    $form->fill([
                        'title' => $record->title,
                        'start' => $arguments['event']['start'] ?? $record->start,
                        'end' => $arguments['event']['end'] ?? $record->end ?? null,
                        'user_id' => $record->user_id,
                        'requirement' => $record->requirement,
                        'status' => $record->status,
                    ]);
                }
            )->hidden(function(){
                return auth()->user()->type !== 0;
            }),

            ActionsDeleteAction::make()->hidden(function(){
                return auth()->user()->type !== 0;
            }),
        ];
    }


    public function getFormSchema(): array
    {
        return [
            Select::make('user_id')
            ->label('User')
            ->options(User::all()->pluck('name' , 'id'))
            ->required()
            ->searchable(),

            TextInput::make('title')->required(),
            ToggleButtons::make('status')
            ->inline()
            ->options(Status::class)
            ->required(),

            Grid::make()
                ->schema([
                    DateTimePicker::make('start')->rules([
                        function () {
                            return function (string $attribute, $value, Closure $fail) {
                                if (Carbon::now()->startOfDay()->equalTo(Carbon::parse($value)->startOfDay()) || !Carbon::now()->greaterThan(Carbon::parse($value))) {
                                    return;
                                } else {
                                    $fail('The :attribute is invalid.');
                                }

                            };
                        },
                    ]),
                    DateTimePicker::make('end'),
                ]),
            MarkdownEditor::make('requirement')->columnSpan(2)->required(),

        ];
    }


}
