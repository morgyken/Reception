<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDocumentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('reception_patient_documents', function(Blueprint $column) {
            $column->increments('id');
            $column->integer('patient')->unsigned();
            $column->string('document_type');
            $column->string('filename');
            $column->string('mime');
            $column->longText('description');
            $column->integer('user')->unsigned();
            $column->timestamps();

            $column->foreign('patient')
                    ->references('id')
                    ->on('reception_patients')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $column->foreign('user')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
        DB::statement("ALTER TABLE reception_patient_documents ADD document LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('reception_patient_documents');
    }

}
