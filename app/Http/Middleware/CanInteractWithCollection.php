<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;
use App\Models\Configuration\ConfigurationRatingRestriction;

class CanInteractWithCollection
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
		$collection = $request->route('collection');
		$ratingRestriction = null;
		$blacklist = null;
		if (Auth::check())
		{
			$blacklist = Auth::user()->blacklisted_collections()->where('collection_id', '=', $collection->id)->first();
			$ratingRestriction = Auth::user()->rating_restriction_configuration->where('rating_id', '=', $collection->rating_id)->first();
		}
		else
		{
			$ratingRestriction = ConfigurationRatingRestriction::where('user_id', '=', null)->where('rating_id', '=', $collection->rating_id)->first();
		}
		
		if ($ratingRestriction != null)
		{
			if (($ratingRestriction->display) && ($blacklist == null))
			{
				return $next($request);
			}
			else
			{
				App::abort(404, 'The specified page was not found. Please check to ensure that the url is correct.');
			}
		}
		else
		{
			App::abort(404, 'The specified page was not found. Please check to ensure that the url is correct.');
		}
    }
}
