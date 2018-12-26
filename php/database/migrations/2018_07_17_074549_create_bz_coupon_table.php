<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBzCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mm_coupon', function (Blueprint $table) {
            $table->increments('coupon_id')->comment('优惠卷id');
            $table->bigInteger('shop_id')->comment('商家ID');
            $table->string('coupon_name',20)->comment('优惠卷名称');
            $table->bigInteger('coupon_type')->comment('优惠卷类型');
            $table->decimal('coupon_money')->comment('优惠卷面额');
            $table->string('coupon_desc',100)->comment('优惠卷描述');
            $table->bigInteger('send_num')->comment('发放数量');
            $table->bigInteger('receive_num')->comment('领取数量');
            $table->date('vaild_start_time')->comment('活动开始时间');
            $table->date('vaild_end_time')->comment('活动结束时间');
            $table->date('create_time')->comment('创建时间');
            $table->integer('dataflag')->comment('有效状态 1:有效 0:删除')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bz_coupon');
    }
}
