<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reception_patients', function(Blueprint $column) {
            $column->increments('id');
            $column->binary('first_name');
            $column->binary('middle_name')->nullable();
            $column->binary('last_name');
            $column->date('dob');
            $column->enum('sex', ['male', 'female']);
            $column->binary('mobile');
            $column->binary('id_no');
            $column->binary('email')->nullable();
            $column->binary('telephone')->nullable();
            $column->binary('alt_number')->nullable();
            $column->binary('address')->nullable();
            $column->string('post_code')->nullable();
            $column->string('town')->nullable();
            $column->smallInteger('status')->default(1);

            $column->softDeletes();
            $column->timestamps();
        });
        DB::statement("ALTER TABLE reception_patients ADD image LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('reception_patients');
    }

}
