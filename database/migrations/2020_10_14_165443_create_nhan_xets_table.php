<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNhanXetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhan_xets', function (Blueprint $table) {
            $table->id('ma_nhan_xet');
            $table->integer('ma_san_pham')->nullable();
            $table->integer('ma_khach_hang');
            $table->text('binh_luan')->default('');
            $table->boolean('isActive')->default(true);
        });

                $faker = Faker\Factory::create();
        $limit = 20;
        $sps = \Illuminate\Support\Facades\DB::table('san_phams')->pluck('ma_san_pham');
//        $sps = array($sp);
        $khs = \Illuminate\Support\Facades\DB::table('tai_khoans')->where('loai_tai_khoan', '=', 'KH')->pluck('ma_tai_khoan');
//        $khs = array($kh);

        for ($i = 0; $i < $limit; $i++) {
            DB::table('nhan_xets')->insert([
                'ma_san_pham' => $faker->randomElement($sps),
                'ma_khach_hang' => $faker->randomElement($khs),
                'binh_luan' => $faker->text,
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
        Schema::dropIfExists('nhan_xets');
    }
}
