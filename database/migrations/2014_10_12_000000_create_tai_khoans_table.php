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
            $table->string('so_dien_thoai')->default('');
            $table->mediumText('hinh_anh')->nullable();
            $table->string('loai_tai_khoan')->default('KH');
            $table->boolean('isActive')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

//        DB::table('tai_khoans')->insert([
//            'email' => 'admin@gmail.com',
//            'mat_khau' => bcrypt('vannam212'),
//            'ho_ten' => 'Văn Nam',
//            'dia_chi' => 'Hà Nội',
//            'so_dien_thoai' => '0123456789',
//            'loai_tai_khoan' => 'QT'
//        ]);

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
