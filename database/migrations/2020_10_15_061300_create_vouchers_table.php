<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id('ma_voucher');
            $table->integer('ma_khach_hang');
            $table->integer('muc_voucher')->default(0);
            $table->boolean('isActive')->default(true);
        });

        $faker = Faker\Factory::create();
        $limit = 20;

        $khs = \Illuminate\Support\Facades\DB::table('tai_khoans')->where('loai_tai_khoan', '=', 'KH')->pluck('ma_tai_khoan');

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        for ($i = 0; $i < $limit; $i++) {
            DB::table('vouchers')->insert([
                'ma_khach_hang' => $faker->randomElement($khs),
                'muc_voucher' => rand(0, 100),
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
        Schema::dropIfExists('vouchers');
    }
}
