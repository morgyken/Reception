<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsNokTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reception_patients_nok', function(Blueprint $column) {
            $column->integer('patient', true)->unsigned();
            $column->binary('first_name');
            $column->binary('middle_name')->nullable();
            $column->binary('last_name');
            $column->string('relationship')->nullable();
            $column->binary('mobile');
            $column->timestamps();

            $column->foreign('patient')
                    ->references('id')
                    ->on('reception_patients')
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
        Schema::drop('reception_patients_nok');
    }

}
