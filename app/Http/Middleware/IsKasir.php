<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsKasir
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'kasir') {
            return $next($request);
        } else {
            return redirect()->route('landing_page')->with('failed', 'Anda bukan kasir! Anda tidak dapat mengakses halaman ini');
        }
    }
}
