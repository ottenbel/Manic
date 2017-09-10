<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;

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
			App::abort(403, 'Access denied');
		}
    }
}
