<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TasksStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskStatus::create(
            [
                'name' => 'to-do',
                'color' => '#FCF032',
                'icon' => 'error_outline'
            ]
        );
        TaskStatus::create(
            [
                'name' => 'in-progress',
                'color' => '#2C54D5',
                'icon' => 'schedule'
            ]
        );
        TaskStatus::create(
            [
                'name' => 'done',
                'color' => '#5CB525',
                'icon' => 'check_circle_outline'
            ]
        );
        TaskStatus::create(
            [
                'name' => 'undone',
                'color' => '#D9421D',
                'icon' => 'highlight_off'
            ]
        );
    }
}
