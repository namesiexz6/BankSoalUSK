<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // ตรวจสอบว่า session มีการตั้งค่าภาษาไว้หรือไม่
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }

        return $next($request);
    }
}

