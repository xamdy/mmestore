<?php
/**
 * Created by PhpStorm
 * Date: 2018/7/12
 * Time: 9:31
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
class CouponUsers extends Base
{
    public $timestamps = false;
    protected $table = 'coupon_users';

}