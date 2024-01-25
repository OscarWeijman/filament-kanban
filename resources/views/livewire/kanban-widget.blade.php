<x-filament-widgets::widget>
    <x-filament::section>
        <div x-data class="flex gap-4">
            @foreach($boards as $board)

                <div class="w-1/2 md:w-1/4">
                    <div
                        class="bg-white/5 rounded p-4"
                        style="min-height: 150px; border: 1px solid rgba(0, 0, 0, 0.1); border-top-width: 8px; border-top-color: {{$board->color}};"
                        @dragover.prevent=""
                        @drop.prevent="$wire.dropItem({{$board->id}})"
                    >
                        <strong>{{ $board->title }}</strong>
                        @foreach($board->tasks as $index=>$task)
                            <div
                                style="background: rgba(255, 255, 255, 0.1); margin: 4px 0;"
                                class="rounded p-2"
                                @drop.prevent="$wire.setDropIndex({{$task->id}}, {{$index}})"
                                @dragstart="$wire.startDragging({{$board->id}}, {{$task->id}}, {{$index}})"
                                draggable="true"
                            >
                                <span>{{ $task->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-row-reverse mt-2">
            <livewire:task-form />
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
