<?php

namespace App\Http\Controllers\User\User\Favourites\Collection;

use App\Http\Controllers\WebController;
use App\Http\Requests\User\User\Favourites\Collection\StoreCollectionFavouriteRequest;
use App\Models\Collection;
use App\Models\User\CollectionFavourite;
use Auth;
use Config;
use ConfigurationLookupHelper;
use DB;
use Illuminate\Http\Request;

class CollectionFavouritesController extends WebController
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
		$this->middleware('canInteractWithCollection')->except('index');
		$this->middleware('permission:Add Favourite Collection')->only('store');
		$this->middleware('permission:Delete Favourite Collection')->only('destroy');
    }
	
	public function index(Request $request)
	{
		$messages = self::GetFlashedMessages($request);
		$userFavourites = Auth::user()->favourite_collections()->pluck('collection_id')->toArray();
		$lookupKey = Config::get('constants.keys.pagination.collectionsPerPageIndex');
		$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		$ratingRestrictions = Auth::user()->rating_restriction_configuration->where('display', '=', false)->pluck('rating_id')->toArray();
		
		$favourites = new Collection();
		$favourites = $favourites->whereIn('id', $userFavourites)->whereNotIn('rating_id', $ratingRestrictions)->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
		
		return View('user.user.favourites.collection.index', array('collections' => $favourites, 'messages' => $messages));
	}
	
	public function store(StoreCollectionFavouriteRequest $request, Collection $collection)
	{
		$collectionFavourite = new CollectionFavourite();
		
		DB::beginTransaction();
		try
		{
			$collectionFavourite->user_id = Auth::user()->id;
			$collectionFavourite->collection_id = $collection->id;
			$collectionFavourite->save();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully add collection to favourites."]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully added collection $collection->name to favourites."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
	}
	
	public function destroy(Collection $collection)
	{
		$collectionFavourite = Auth::user()->favourite_collections()->where('collection_id', '=', $collection->id)->first();
		
		DB::beginTransaction();
		try
		{
			if ($collectionFavourite != null)
			{
				$collectionFavourite->forceDelete();
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully remove $collection->name from favourites."]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully removed collection $collection->name from favourites."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
	}
}