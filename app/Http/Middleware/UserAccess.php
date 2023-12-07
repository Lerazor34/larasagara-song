<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccess
{
    protected $prefix;

    public function __construct(Request $request)
    {
        $this->prefix = $request->route()->getName();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::allowedUri($this->prefix)) return $next($request);
        abort(403, 'unauthorized');
        // return back()->withErrors('You are not allowed to access!');
    }
}
