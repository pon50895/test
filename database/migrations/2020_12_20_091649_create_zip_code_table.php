<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('zip_code')) {
            return 'There is the table zip_code existing';
        }

        Schema::create('zip_code', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('file_name');
            $table->integer('zip_code')->index('zip_code');
            $table->string('city')->index('city');
            $table->string('area')->index('area');
            $table->string('street')->index('street');
            $table->string('spelling')->index('spelling');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zip_code');
    }
}
