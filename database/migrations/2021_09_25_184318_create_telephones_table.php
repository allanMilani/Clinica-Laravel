<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelephonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Telephones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('areaCode')->length(2)->unsigned()->nullable(false);
            $table->integer('number')->length(2)->unsigned()->nullable(false);
            $table->text('description');
            $table->foreignId('phisician_id')->constrained('Phisician');
            $table->foreignId('patient_id')->constrained('Patient');
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
        Schema::dropIfExists('Telephones');
    }
}
