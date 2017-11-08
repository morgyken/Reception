<?php

namespace Ignite\Reception\Console;

use Ignite\Reception\Entities\Patients;
use Illuminate\Console\Command;

class FixPatientNumber extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'reception:patient-no';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mock patient number';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $max = (int)Patients::max('patient_no');
        $above = Patients::whereNull('patient_no')->get();
        foreach ($above as $p) {
            $max++;
            $p->patient_no = $max;
            $p->save();
        }
        $this->warn('Done!');
    }
}
