<?php

namespace App\Http\Middleware;

use Closure;

class BusinessAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 如果不是管理员或者没有登录;则重定向到登录页面
        if (session('is_business') !== 1) {
            return redirect('business/login/index');
        }
        return $next($request);
    }
}
