<?php

namespace Ignite\Reception\Database\Seeders;

use Ignite\Reception\Entities\AppointmentCategory;
use Illuminate\Database\Seeder;

class ScheduleCategoriesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $schedule_cats = mconfig('reception.options.schedule_categories');
        foreach ($schedule_cats as $c) {
            $cat = new AppointmentCategory;
            $cat->name = $c;
            $cat->save();
        }
    }

}
