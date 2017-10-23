<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReduceRequiredFieldsInPatientInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reception_patient_schemes', function (Blueprint $table) {
            $table->string('policy_number')->nullable()->change();
            $table->string('principal')->nullable()->change();
            $table->string('dob')->nullable()->change();
            $table->smallInteger('relationship')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
