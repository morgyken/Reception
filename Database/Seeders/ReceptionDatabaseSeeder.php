<?php

namespace Ignite\Reception\Database\Seeders;

use Illuminate\Database\Seeder;

class ReceptionDatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(ScheduleCategoriesTableSeeder::class);
        $this->call(PatientsTableSeeder::class);

        // $this->call("OthersTableSeeder");
    }

}
