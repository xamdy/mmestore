<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    // 软删除
    use SoftDeletes;

   protected $dates = ['delete_at'];
    
}
