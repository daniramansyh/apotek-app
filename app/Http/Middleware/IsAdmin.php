<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'admin') {
            return $next($request);
        } else {
            return redirect()->route('landing_page')->with('failed', 'Anda bukan admin! Anda tidak dapat mengakses halaman ini');
        }
    }
}
