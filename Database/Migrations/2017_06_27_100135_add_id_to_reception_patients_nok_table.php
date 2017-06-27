<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdToReceptionPatientsNokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reception_patients_nok', function (Blueprint $table) {
            $table->dropPrimary();
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reception_patients_nok', function (Blueprint $table) {
            $table->dropColumn(['id']);
            $table->integer('patient', true)->unsigned()->change();
        });
    }
}
