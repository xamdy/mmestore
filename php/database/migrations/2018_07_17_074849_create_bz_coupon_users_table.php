<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBzCouponUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_coupon_users', function (Blueprint $table) {
            $table->increments('coupon_id')->comment('优惠卷id');
            $table->integer('user_id')->comment('用户ID');;
            $table->integer('receive_time')->comment('领取时间');
            $table->integer('coupon_status')->comment('优惠卷状态');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bz_coupon_users');
    }
}
