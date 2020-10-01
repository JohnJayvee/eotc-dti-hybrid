<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvinceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('province', function ($table) {
            $table->increments('id');
            $table->string('psgcCode', 100);
            $table->string('provDesc', 100);
            $table->string('regCode',10);
            $table->string('provCode', 10)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        $filepath = storage_path() . '/addresses/refprovince.json';
        $file = json_decode(file_get_contents($filepath,true))->RECORDS;

        foreach ($file as $key => $province) {
          App\Laravel\Models\Province::create((array)$province)->save();
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
        Schema::dropIfExists('province');
    }
}
