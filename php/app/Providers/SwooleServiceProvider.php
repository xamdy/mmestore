<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/10/17
 * Time: 15:28
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Handle\SwooleHandle;

class SwooleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     *
     */
    protected $namespace = 'App\Http\Controllers';
    public function boot()
    {
        //
    }
    public function register()
    {
        $this->app->singleton('swoole',function(){
            return new SwooleHandle();
        });
    }
}
