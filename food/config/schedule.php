<?php

use Illuminate\Support\Facades\Http;

return [

    /*
    |--------------------------------------------------------------------------
    | Scheduled Tasks
    |--------------------------------------------------------------------------
    |
    | Here you may define all of your scheduled tasks that should be run at the
    | frequency you specify. Laravel provides a clean, fluent API for defining
    | basic tasks as well as more complex tasks using closures or classes.
    |
    */

    'tasks' => [

        // Tarefa agendada para importar os dados do Open Food Facts diariamente às 01:00
        [
            'name' => 'Import Data from Open Food Facts',
            'description' => 'Import the latest data from Open Food Facts',
            'command' => function () {
                Http::get(url('import-data'));
            },
            'schedule' => 'dailyAt(01:00)',
            'output' => 'import.log',
            'timezone' => 'UTC',
        ],

    ],

];
