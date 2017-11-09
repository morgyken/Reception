<?php

namespace Ignite\Reception\Database\Seeders;

use Faker\Factory;
use Ignite\Reception\Entities\NextOfKin;
use Ignite\Reception\Entities\PatientInsurance;
use Ignite\Reception\Entities\Patients;
use Illuminate\Database\Seeder;

class PatientsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i = 0; $i < 50; $i++) {
            $faker = Factory::create();
            \DB::beginTransaction();
            //patient first
            $patient = new Patients;
            $patient->sex = $faker->randomElement(['male', 'female']);
            $patient->first_name = ucfirst($faker->firstName($patient->sex));
//            $patient->middle_name = ucfirst($faker->firstName);
            $patient->last_name = ucfirst($faker->lastName);
            $patient->id_no = $faker->numberBetween(10000000, 99999999);
            $patient->dob = $faker->date();
            $patient->telephone = $faker->numberBetween(10000000, 99999999); //$faker->phoneNumber;
            $patient->mobile = '07' . $faker->numberBetween(10000000, 38999999);
            $patient->alt_number = '07' . $faker->numberBetween(10000000, 38999999); //$faker->phoneNumber;
            $patient->email = strtolower($faker->email);
            $patient->address = $faker->address;
            $patient->post_code = $faker->numberBetween(1000, 90000);
            $patient->town = ucfirst($faker->city);
            $patient->save();
            //next of kins
            $nok = new NextOfKin;
            $nok->patient = $patient->id;
            $nok->first_name = ucfirst($faker->firstName);
//            $nok->middle_name = ucfirst($faker->firstName);
            $nok->last_name = ucfirst($faker->lastName);
            $nok->mobile = $faker->phoneNumber;
            $nok->relationship = $faker->numberBetween(0, 8);
            $nok->save();
            $count = random_int(0, 15);
            for ($_i = 1; $_i < $count; $_i++) {
                $schemes = new PatientInsurance;
                $schemes->patient = $patient->id;
                $schemes->scheme = $_i;
                $schemes->policy_number = $faker->randomNumber();
                $schemes->principal = ucwords($faker->name);
                $schemes->dob = $faker->dateTime();
                $schemes->relationship = $_i;
                $schemes->save();
            }
            \DB::commit();
        }
    }

}
