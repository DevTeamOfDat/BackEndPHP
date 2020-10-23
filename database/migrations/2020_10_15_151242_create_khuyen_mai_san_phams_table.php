<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhuyenMaiSanPhamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khuyen_mai_san_phams', function (Blueprint $table) {
            $table->id();
            $table->integer('ma_san_pham')->nullable();
            $table->integer('ma_loai_san_pham')->nullable();
            $table->integer('ma_thuong_hieu')->nullable();
            $table->integer('ma_ngay_khuyen_mai');
            $table->integer('muc_khuyen_mai')->default(0)->comment('%');
            $table->boolean('isActive')->default(true);
        });

        $faker = Faker\Factory::create();
        $limit = 20;

        $ths = \Illuminate\Support\Facades\DB::table('thuong_hieus')->pluck('ma_thuong_hieu');
        $lsps = \Illuminate\Support\Facades\DB::table('loai_san_phams')->pluck('ma_loai_san_pham');
        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
        $nkms = \Illuminate\Support\Facades\DB::table('ngay_khuyen_mais')->pluck('ma_ngay_khuyen_mai');

        for ($i = 0; $i < $limit; $i++) {
            DB::table('khuyen_mai_san_phams')->insert([
                'ma_san_pham' => $faker->randomElement($sps),
                'ma_loai_san_pham' => $faker->randomElement($lsps),
                'ma_thuong_hieu' => $faker->randomElement($ths),
                'ma_ngay_khuyen_mai' => $faker->randomElement($nkms),
                'muc_khuyen_mai' => rand(0, 100),
                'isActive' => $faker->randomElement([true, false]),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khuyen_mai_san_phams');
    }
}
