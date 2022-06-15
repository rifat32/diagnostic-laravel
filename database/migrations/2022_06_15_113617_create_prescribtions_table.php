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
            $table->unsignedBigInteger("patient_id");
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->text("description");
            $table->text("note");
            $table->date("next_appointment");
            $table->string("fees");
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
