<?php

namespace App\Http\Controllers\User\User\Blacklists\Collection;

use App\Http\Controllers\WebController;
use App\Http\Requests\User\User\Blacklists\Collection\StoreCollectionBlacklistRequest;
use App\Models\Collection;
use App\Models\User\CollectionBlacklist;
use Auth;
use Config;
use ConfigurationLookupHelper;
use DB;
use Illuminate\Http\Request;

class CollectionBlacklistController extends WebController
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
		//$this->middleware('canInteractWithCollection')->except('index'); //Add custom middleware to handle this
		$this->middleware('permission:Add Blacklisted Collection')->only('store');
		$this->middleware('permission:Delete Blacklisted Collection')->only('destroy');
    }
	
	public function index(Request $request)
	{
		$this->GetFlashedMessages($request);
		$userBlacklist = Auth::user()->blacklisted_collections()->pluck('collection_id')->toArray();
		$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		
		$blacklisted = new Collection();
		
		$blacklisted = $blacklisted->whereIn('id', $userBlacklist)->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
		
		return View('user.user.blacklist.collection.index', array('collections' => $blacklisted, 'messages' => $this->messages));
	}
	
	public function store(StoreCollectionBlacklistRequest $request, Collection $collection)
	{
		$collectionBlacklist = new CollectionBlacklist();
		
		DB::beginTransaction();
		try
		{
			$collectionBlacklist->user_id = Auth::user()->id;
			$collectionBlacklist->collection_id = $collection->id;
			$collectionBlacklist->save();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully add collection to blacklist.", ['collection' => $collection->id, 'error' => $e]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully added collection $collection->name to blacklist.", ['collection' => $collection->id]);
		return redirect()->route('index_collection')->with("messages", $this->messages);
	}
	
	public function destroy(Collection $collection)
	{
		$collectionBlacklist = Auth::user()->blacklisted_collections()->where('collection_id', '=', $collection->id)->first();
		
		DB::beginTransaction();
		try
		{
			if ($collectionBlacklist != null)
			{
				$collectionBlacklist->forceDelete();
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully remove $collection->name from blacklist.", ['collection' => $collection->id, 'error' => $e]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully removed collection $collection->name from blacklist.", ['collection' => $collection->id]);		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $this->messages);
	}
}