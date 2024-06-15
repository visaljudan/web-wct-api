<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Other middleware entries
        \App\Http\Middleware\Cors::class,
    ];
}
