<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // kalo uda login bole akses ke url yg diminta
            return $next($request);
        } else {
            // klo blm di lempar ke hal login
            return redirect()->route('login')->with('failed','Silahkan login terlebih dahulu');
        }
    }
}
