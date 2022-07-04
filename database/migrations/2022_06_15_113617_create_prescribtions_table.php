<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescribtionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescribtions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("patient_id")->nullable();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->unsignedBigInteger("appointment_id");
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->text("description")->nullable();
            $table->text("note")->nullable();
            $table->text("patient_history")->nullable();

            $table->date("next_appointment")->nullable();
            $table->string("fees")->nullable();
            $table->text("medical_history")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescribtions');
    }
}
