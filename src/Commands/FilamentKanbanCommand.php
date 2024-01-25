<?php

namespace OscarWeijman\FilamentKanban\Commands;

use Illuminate\Console\Command;

class FilamentKanbanCommand extends Command
{
    public $signature = 'make:kanban';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
