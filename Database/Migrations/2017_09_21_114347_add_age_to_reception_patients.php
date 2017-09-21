<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgeToReceptionPatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE reception_patients CHANGE COLUMN sex sex VARCHAR(9) ");
        Schema::table('reception_patients', function (Blueprint $table) {
            $table->string('age',3)->nullable()->after('dob');
            $table->string('age_in',10)->nullable()->after('age');
            $table->date('dob')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reception_patients', function (Blueprint $table) {
                $table->dropColumn(['age','age_in']);
                $table->date('dob')->change();
        });
        //DB::statement("ALTER TABLE reception_patients CHANGE COLUMN sex sex ENUM('male', 'female', 'other')");
    }
}
