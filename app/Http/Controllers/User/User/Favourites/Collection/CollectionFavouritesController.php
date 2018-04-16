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
		parent::__construct();
		
		$this->paginationKey = "pagination_collections_per_page_index";
		$this->placeholderStub = "collection";
		
        $this->middleware('auth');
		$this->middleware('canInteractWithCollection')->except('index');
		$this->middleware('permission:Add Favourite Collection')->only('store');
		$this->middleware('permission:Delete Favourite Collection')->only('destroy');
    }
	
	public function index(Request $request)
	{
		$this->GetFlashedMessages($request);
		$userFavourites = Auth::user()->favourite_collections()->pluck('collection_id')->toArray();
		$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		$ratingRestrictions = Auth::user()->rating_restriction_configuration->where('display', '=', false)->pluck('rating_id')->toArray();
		
		$favourites = new Collection();
		$favourites = $favourites->whereIn('id', $userFavourites)->whereNotIn('rating_id', $ratingRestrictions)->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
		
		return View('user.user.favourites.collection.index', array('collections' => $favourites, 'messages' => $this->messages));
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
			$this->AddWarningMessage("Unable to successfully add collection $collection->name to favourites.", ['collection' => $collection->id, 'error' => $e]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully added collection $collection->name to favourites.");
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
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
			$this->AddWarningMessage("Unable to successfully remove $collection->name from favourites.", ['collection' => $collection->id, 'error' => $e]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully removed collection $collection->name from favourites.");
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
	}
}