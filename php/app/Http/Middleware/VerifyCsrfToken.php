<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // 排除csrf验证
        // 'home/*',
        // 'admin/*',
        'api/*',
        'api/Wxpay/notify',
        'admin/goods/imgUpload',
    ];
}
