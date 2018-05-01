<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Rating;
use Config;
use ConfigurationLookupHelper;
use DB;
use App\Http\Requests\Rating\StoreRatingRequest;
use App\Http\Requests\Rating\UpdateRatingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Input;
use App\Models\User;
use SeedingHelper;

class RatingController extends WebController
{

    public function __construct()
    {
        parent::__construct();

        $this->paginationKey = "pagination_rating_per_page_index";
        $this->placeholderStub = "rating";
        $this->placeheldFields = array('name','priority');

        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('permission:Create Status')->only(['create', 'store']);
        $this->middleware('permission:Edit Status')->only(['edit', 'update']);
        $this->middleware('permission:Delete Status')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $order = trim(strtolower($request->input('order')));
        if (($order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
            && ($order != Config::get('constants.sortingStringComparison.listOrder.descending')))
        {
            $order = Config::get('constants.sortingStringComparison.listOrder.ascending');
        }
        
        $this->GetFlashedMessages($request);
        $paginationRatingPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
        
        $ratings = new Rating();
        $ratings = $ratings->orderBy('name', $order)->paginate($paginationRatingPerPageIndexCount);
        $ratings->appends(Input::except('page'));
        
        return View('rating.index', array('ratings' => $ratings, 'list_order' => $order, 'messages' => $this->messages));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $this->authorize(Rating::class);
        $this->GetFlashedMessages($request);
        $configurations = $this->GetConfiguration();
        return View('rating.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreRatingRequest $request)
    {
        $rating = new Rating();
        return $this->InsertOrUpdate($request, $rating, 'created', 'create', true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, Rating $rating)
    {
        $this->GetFlashedMessages($request);
        $usageCount = $rating->collections()->count();
        
        return View('rating.show', array('rating' => $rating, 'usageCount' => $usageCount, 'messages' => $this->messages));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, Rating $rating)
    {
        $this->authorize($rating);
        $this->GetFlashedMessages($request);
        $configurations = $this->GetConfiguration();
        
        return View('rating.edit', array('configurations' => $configurations, 'rating' => $rating, 'messages' => $this->messages));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        return $this->InsertOrUpdate($request, $rating, 'updated', 'update', false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Rating $rating)
    {
        $this->authorize($rating);
        $ratingName = $rating->name;
        
        DB::beginTransaction();
        try
        {
            //Force deleting for now, build out functionality for soft deleting later.
            $rating->forceDelete();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $this->AddWarningMessage("Unable to successfully delete rating $ratingName.", ['rating' => $rating->id, 'error' => $e]);
            return Redirect::back()->with(["messages" => $this->messages])->withInput();
        }
        DB::commit();
        
        $this->AddSuccessMessage("Successfully purged status $ratingName from the database.");
        return redirect()->route('index_status')->with("messages", $this->messages);
    }

    private function InsertOrUpdate($request, $rating, $action, $errorAction, $isCreating)
    {
        DB::beginTransaction();
        try
        {
            $rating->fill($request->only(['name', 'priority']));    
            $rating->save();

            //Creating the appropriate entry to control the middleware when creating a new rating 
            if ($isCreating)
            {
                $users = User::all();
                foreach($users as $user)
                {
                    SeedingHelper::SeedRatingRestrictionRow($user, $user, $rating->id, true, $rating->priority);
                }
            }
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $this->AddWarningMessage("Unable to successfully $errorAction rating $rating->name.", ['rating' => $rating->id, 'error' => $e]);
            return Redirect::back()->with(["messages" => $this->messages])->withInput();
        }
        DB::commit();
        
        $this->AddSuccessMessage("Successfully $action rating $rating->name.");
        return redirect()->route('show_rating', ['rating' => $rating])->with("messages", $this->messages);
    }
}
