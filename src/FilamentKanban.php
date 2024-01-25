<?php

namespace OscarWeijman\FilamentKanban;

use App\Models\Board;
use App\Models\Task;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FilamentKanban extends Widget
{
    protected static string $view = 'livewire.kanban-widget';
    protected int | string | array $columnSpan = 'full';

    public $boards = [];

    public $boardId;
    public $taskId;
    public $index;
    public $dropIndex;
    public $dropTaskId;


//    public array $boards = [
//        [
//            'id'    => 1,
//            'title' =>  'Todo',
//            'color' => '#DE6B1F'
//        ],
//        [
//            'id'    => 2,
//            'title' =>  'In Progress',
//            'color' => '#D59E2E'
//        ],
//        [
//            'id'    => 3,
//            'title' =>  'Review',
//            'color' => '#3182CE'
//        ],
//        [
//            'id'    => 4,
//            'title' =>  'Done',
//            'color' => '#37A169'
//        ]
//    ];

    public function mount(): void
    {
        $boards = Board::with('tasks')->get();
        $this->boards = $boards;
    }


    public function startDragging($boardId, $taskId, $index)
    {
        $this->boardId = $boardId;
        $this->taskId = $taskId;
        $this->index = $index;
    }

    public function setDropIndex($taskId, $index) {
        $this->dropTaskId = $taskId;
        $this->dropIndex = $index;
    }

    public function dropItem($boardId)
    {
        $dropIndex = $this->dropIndex;
        $this->reorderItems(Task::class, $this->taskId, $dropIndex, $boardId);
    }

    private function reorderItems($modelClassName, $taskId, $newOrder, $newBoardId = null) {
        DB::transaction(function () use ($modelClassName, $taskId, $newOrder, $newBoardId) {
            // Vind de taak en krijg de oude waarden
            $task = $modelClassName::find($taskId);
            $oldOrder = $task->order;
            $oldBoardId = $task->board_id;

            // Als $newOrder leeg is en de taak wordt verplaatst naar een nieuw bord,
            // zet $newOrder dan op 0 omdat dit de eerste taak op het nieuwe bord zal zijn
            if ($newBoardId !== null && $newBoardId != $oldBoardId && (empty($newOrder) || $newOrder === null)) {
                $newOrder = 0;
            }

            $isMovingToDifferentBoard = $newBoardId !== null && $newBoardId != $oldBoardId;

            if ($isMovingToDifferentBoard) {
                // Verlaag de 'order' van taken in het oude bord
                $modelClassName::where('board_id', $oldBoardId)
                    ->where('order', '>', $oldOrder)
                    ->decrement('order');

                // Verhoog de 'order' van taken in het nieuwe bord, als er andere taken zijn
                if ($modelClassName::where('board_id', $newBoardId)->exists()) {
                    $modelClassName::where('board_id', $newBoardId)
                        ->where('order', '>=', $newOrder)
                        ->increment('order');
                }

                // Update de taak zelf
                $task->board_id = $newBoardId;
                $task->order = $newOrder;
            } else {
                // Het item wordt binnen hetzelfde bord verplaatst
                if ($newOrder > $oldOrder) {
                    $modelClassName::where('board_id', $oldBoardId)
                        ->whereBetween('order', [$oldOrder + 1, $newOrder])
                        ->decrement('order');
                } elseif ($newOrder < $oldOrder) {
                    $modelClassName::where('board_id', $oldBoardId)
                        ->whereBetween('order', [$newOrder, $oldOrder - 1])
                        ->increment('order');
                }
                // Update de taak zelf
                $task->order = $newOrder;
            }

            // Sla de bijgewerkte taak op
            $task->save();
        });
    }

    public function render(): View
    {
        return view('filament-kanban::livewire.kanban-widget');
    }
}
