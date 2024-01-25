<?php

namespace OscarWeijman\FilamentKanban\App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use OscarWeijman\FilamentKanban\Models\Board;
use OscarWeijman\FilamentKanban\Models\Task;

class TaskForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function taskAction(): Action
    {
        $boards = [];
        if (Board::count()) {
            $board = Board::all()->pluck('title', 'id');
        }

        return Action::make('task')
           // ->slideOver()
            ->form([
                Select::make('board_id')
                    ->required()
                    ->label('Board')
                    ->options(Board::all()->pluck('title', 'id'))
                    ->createOptionForm([
                        TextInput::make('title')->required(),
                        ColorPicker::make('color')->required(),
                    ])
                    ->createOptionUsing(function ($data) {
                        Board::create([
                            'title' => $data['title'],
                            'color' => $data['color'],
                        ]);
                    }),
                Repeater::make('tasks')->schema([
                    TextInput::make('name')->label('Naam')->required(),
                ]),

            ])
            ->requiresConfirmation()
            ->action(function (array $data) {
                $order = 0;
                foreach ($data['tasks'] as $task) {
                    Task::create([
                        'board_id' => $data['board_id'],
                        'name' => $task['name'],
                        'status' => 1,
                        'order' => $order,
                    ]);
                    $order++;
                }
                $this->redirect('admin');
            });
    }

    public function render()
    {
        return view('filament-kanban::livewire.task-form');
    }
}
