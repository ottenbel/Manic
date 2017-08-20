<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckIsEditor
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
		if (Auth::user()->has_editor_permission())
		{
			return $next($request);
		}
		else
		{
			return back()->withInput();
		}
    }
}
