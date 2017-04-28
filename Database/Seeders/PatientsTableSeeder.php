<?php

namespace Ignite\Reception\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        for ($i = 0; $i < 20; $i++) {
            $faker = \Faker\Factory::create();
            DB::transaction(function() use ($faker) {
                //patient first
                $patient = new \Ignite\Reception\Entities\Patients;
                $patient->first_name = ucfirst($faker->firstName);
                $patient->middle_name = ucfirst($faker->firstName);
                $patient->last_name = ucfirst($faker->lastName);
                $patient->id_no = $faker->numberBetween(10000000, 99999999);
                $patient->dob = $faker->date();
                $patient->sex = $faker->randomElement(['male', 'female']);
                $patient->telephone = $faker->numberBetween(10000000, 99999999); //$faker->phoneNumber;
                $patient->mobile = '07' . $faker->numberBetween(10000000, 38999999);
                $patient->alt_number = '07' . $faker->numberBetween(10000000, 38999999); //$faker->phoneNumber;
                $patient->email = strtolower($faker->email);
                $patient->address = $faker->address;
                $patient->post_code = $faker->numberBetween(1000, 90000);
                $patient->town = ucfirst($faker->city);
                $patient->save();
                //next of kins
                $nok = new \Ignite\Reception\Entities\NextOfKin;
                $nok->patient = $patient->id;
                $nok->first_name = ucfirst($faker->firstName);
                $nok->middle_name = ucfirst($faker->firstName);
                $nok->last_name = ucfirst($faker->lastName);
                $nok->mobile = $faker->phoneNumber;
                $nok->relationship = $faker->numberBetween(0, 8);
                $nok->save();
                $count = mt_rand(0, 10);
                for ($i = 1; $i < $count; $i++) {
                    $schemes = new \Ignite\Reception\Entities\PatientInsurance;
                    $schemes->patient = $patient->id;
                    $schemes->scheme = $i;
                    $schemes->policy_number = $faker->randomNumber();
                    $schemes->principal = ucwords($faker->name);
                    $schemes->dob = $faker->dateTime();
                    $schemes->relationship = $i;
                    $schemes->save();
                }
            });
        }
    }

}
