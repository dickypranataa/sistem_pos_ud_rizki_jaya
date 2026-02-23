<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {

        //cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        //if else user admin dan kasir
        if (Auth::user()->role !== $role) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if (Auth::user()->role === 'kasir') {
                return redirect()->route('kasir.dashboard');
            }
        }

        
        return $next($request);
        
    }
}
