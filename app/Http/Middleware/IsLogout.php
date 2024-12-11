<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsLogout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() == FALSE) {
            return $next($request);
        } else {
            return redirect()->route('landing_page')->with('failed', 'Anda sudah login! Tidak dapat melakukan pemrosesan login kembali !');
        }
    }
}
