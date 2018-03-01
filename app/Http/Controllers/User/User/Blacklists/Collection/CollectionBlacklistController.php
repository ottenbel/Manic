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
        $this->middleware('auth');
		//$this->middleware('canInteractWithCollection')->except('index'); //Add custom middleware to handle this
		$this->middleware('permission:Add Blacklisted Collection')->only('store');
		$this->middleware('permission:Delete Blacklisted Collection')->only('destroy');
    }
	
	public function index(Request $request)
	{
		$messages = self::GetFlashedMessages($request);
		$userBlacklist = Auth::user()->blacklisted_collections()->pluck('collection_id')->toArray();
		$lookupKey = Config::get('constants.keys.pagination.collectionsPerPageIndex');
		$paginationCollectionsPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$blacklisted = new Collection();
		$blacklisted = $blacklisted->whereIn('id', $userBlacklist)->orderBy('updated_at', 'desc')->paginate($paginationCollectionsPerPageIndexCount);
		
		return View('user.user.blacklist.collection.index', array('collections' => $blacklisted, 'messages' => $messages));
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully add collection to blacklist."]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully added collection $collection->name to blacklist."], null, null);
		
		return redirect()->route('index_collection')->with("messages", $messages);
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully remove $collection->name from blacklist."]);
			return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully removed collection $collection->name from blacklist."], null, null);
		
		return redirect()->route('show_collection', ['collection' => $collection])->with("messages", $messages);
	}
}