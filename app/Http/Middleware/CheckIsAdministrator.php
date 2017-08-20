<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckIsAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		if (Auth::user()->has_administrator_permission())
		{
			return $next($request);
		}
		else
		{
			return back()->withInput();
		}
    }
}
