<?php

namespace Database\Seeders;

use App\Models\NotificationsType;
use Illuminate\Database\Seeder;

class NotificationsTypesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        NotificationsType::create([
           'name'=>'E-mail',
           'color'=>'#ffff00'
        ]);
        NotificationsType::create([
            'name'=>'FB',
            'color'=>'#0000ff'
        ]);
        NotificationsType::create([
            'name'=>'Push',
            'color'=>'#33cc33'
        ]);
    }
}
