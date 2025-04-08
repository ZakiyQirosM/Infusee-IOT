<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthPegawai
{
    public function handle($request, Closure $next)
    {
        if (!auth('pegawai')->check()) {
            return redirect('/login');
        }

        return $next($request);
    }

}

