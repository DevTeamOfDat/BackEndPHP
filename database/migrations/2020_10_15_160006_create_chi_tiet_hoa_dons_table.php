<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChiTietHoaDonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('ma_hoa_don');
            $table->integer('ma_san_pham');
            $table->double('gia_ban', 15, 2)->default(0.00);
            $table->integer('so_luong')->default(0);
            $table->boolean('isActive')->default(true);
        });

        $faker = Faker\Factory::create();
        $limit = 20;
        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
        $hds = \Illuminate\Support\Facades\DB::table('hoa_dons')->pluck('ma_hoa_don');

        for ($i = 0; $i < $limit; $i++) {
            DB::table('chi_tiet_hoa_dons')->insert([
                'ma_hoa_don' => $faker->randomElement($hds),
                'ma_san_pham' => $faker->randomElement($sps),
                'gia_ban' => $faker->randomNumber(),
                'so_luong' => $faker->randomNumber(),
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
        Schema::dropIfExists('chi_tiet_hoa_dons');
    }
}
