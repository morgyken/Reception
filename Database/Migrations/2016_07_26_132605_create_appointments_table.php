<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reception_appointments', function(Blueprint $column) {
            $column->increments('id');
            $column->integer('patient')->unsigned()->nullable();
            $column->string('guest')->nullable();
            $column->string('phone')->nullable();
            $column->dateTime('time');
            $column->integer('category')->unsigned();
            $column->integer('doctor')->unsigned()->nullable();
            $column->longText('instructions')->nullable();
            $column->smallInteger('status')->default(1);
            $column->integer('clinic')->unsigned()->nullable();
            $column->timestamps();

            //relations
            $column->foreign('patient')
                    ->references('id')
                    ->on('reception_patients')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $column->foreign('clinic')->references('id')->on('settings_clinics')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $column->foreign('category')->references('id')->on('reception_appointment_categories')
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
        Schema::drop('reception_appointments');
    }

}
