<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('region', function($table) {
            $table->increments('id');
            $table->string('psgcCode', 100);
            $table->string('regDesc', 100);
            $table->string('regCode',10);
            $table->timestamps();
            $table->softDeletes();
        });

        $filepath = storage_path() . '/addresses/refregion.json';
        $file = json_decode(file_get_contents($filepath,true))->RECORDS;

        foreach ($file as $key => $region) {
          App\Laravel\Models\Region::create((array)$region)->save();
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
        Schema::dropIfExists('region');
    }
}
