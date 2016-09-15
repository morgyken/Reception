<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientSchemesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reception_patient_schemes', function(Blueprint $column) {
            $column->increments('id');
            $column->integer('patient')->unsigned();
            $column->integer('scheme')->unsigned();
            $column->string('policy_number');
            $column->string('principal');
            $column->date('dob');
            $column->smallInteger('relationship');
            $column->timestamps();

            $column->foreign('patient')
                    ->references('id')
                    ->on('reception_patients')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $column->foreign('scheme')->references('id')->on('settings_schemes')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('reception_patient_schemes');
    }

}
