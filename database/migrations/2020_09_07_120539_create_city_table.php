<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('city', function ($table) {
            $table->increments('id');
            $table->string('psgcCode',100);
            $table->string('citymunDesc', 100)->default('');
            $table->string('regDesc',10);
            $table->string('provCode',10);
            $table->string('citymunCode',10)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        $filepath = storage_path() . '/addresses/refcitymun.json';
        $file = json_decode(file_get_contents($filepath,true))->RECORDS;

        foreach ($file as $key => $municipality) {
          App\Laravel\Models\City::create((array)$municipality)->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('city');
    }
}
