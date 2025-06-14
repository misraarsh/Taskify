<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class loginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
   
    if (!session()->has('user')) {
        return redirect()->route('login');
    }

    $user = session('user');
    if (!isset($user['role'])) {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    return $next($request);
}

}
