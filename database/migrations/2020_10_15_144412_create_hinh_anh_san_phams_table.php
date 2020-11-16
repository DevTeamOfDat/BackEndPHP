<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHinhAnhSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hinh_anh_san_phams', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ma_san_pham');
            $table->string('hinh_anh')->unique();
            //            $table->longText('hinh_anh')->unique();
            $table->boolean('isActive')->default(true);
        });

//        $faker = Faker\Factory::create();
//        $limit = 20;
//
//        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('hinh_anh_san_phams')->insert([
//                'ma_san_pham' => $faker->randomElement($sps),
//                'hinh_anh' => $faker->imageUrl(),
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
        Schema::dropIfExists('hinh_anh_san_phams');
    }
}
