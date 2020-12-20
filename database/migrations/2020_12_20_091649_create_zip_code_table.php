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

        Schema::table('zip_code', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->int('zip_code')->index('zip_code');
            $table->string('city_name')->index('city_name');
            $table->string('area_name')->index('area_name');
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
