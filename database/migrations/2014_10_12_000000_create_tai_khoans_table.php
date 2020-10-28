<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTaiKhoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tai_khoans', function (Blueprint $table) {
            $table->id('ma_tai_khoan');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->default(now());
            $table->string('mat_khau');
            $table->string('ho_ten', 50);
            $table->string('dia_chi')->default('');
            $table->string('so_dien_thoai', 10)->default('');
            $table->string('hinh_anh')->default('');
            $table->string('loai_tai_khoan')->default('KH');
            $table->boolean('isActive')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
//
//        $faker = Faker\Factory::create();
//        $limit = 20;
//
//        for ($i = 0; $i < $limit; $i++) {
//            DB::table('tai_khoans')->insert([
//                'email' => $faker->email,
//                'email_verified_at' => $faker->date(now()),
//                'mat_khau' => $faker->password(8),
//                'ho_ten' => $faker->name,
//                'dia_chi' => $faker->address,
//                'so_dien_thoai' => $faker->randomNumber(),
//                'hinh_anh' => $faker->imageUrl(),
//                'loai_tai_khoan' => $faker->randomElement(['KH', 'NV', 'QT']),
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
        Schema::dropIfExists('tai_khoans');
    }
}
