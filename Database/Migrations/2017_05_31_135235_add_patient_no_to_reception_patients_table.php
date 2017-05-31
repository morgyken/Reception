<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPatientNoToReceptionPatientsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('reception_patients', function (Blueprint $table) {
            $table->string('patient_no', 200)
                    ->after('id')
                    ->unique()
                    ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('reception_patients', function (Blueprint $table) {
            $table->dropColumn('patient_no');
        });
    }

}
