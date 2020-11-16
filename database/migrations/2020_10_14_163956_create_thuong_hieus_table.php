<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateThuongHieusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thuong_hieus', function (Blueprint $table) {
            $table->id('ma_thuong_hieu');
            $table->string('ten_thuong_hieu');
            $table->mediumText('hinh_anh')->nullable();
            $table->boolean('isActive')->default(true);
        });

//                $faker = Faker\Factory::create();
//        $limit = 20;
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('thuong_hieus')->insert([
//                'ten_thuong_hieu' => $faker->company,
//                'isActive' => $faker->randomElement([true, false]),
//            ]);
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thuong_hieus');
    }
}
