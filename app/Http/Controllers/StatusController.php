<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Status;
use Config;
use ConfigurationLookupHelper;
use DB;
use App\Http\Requests\Status\StoreStatusRequest;
use App\Http\Requests\Status\UpdateStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Input;

class StatusController extends WebController
{
    public function __construct()
    {
        parent::__construct();
        
        $this->paginationKey = "pagination_status_per_page_index";
        $this->placeholderStub = "status";
        $this->placeheldFields = array('name','priority');
        
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('permission:Create Status')->only(['create', 'store']);
        $this->middleware('permission:Edit Status')->only(['edit', 'update']);
        $this->middleware('permission:Delete Status')->only('destroy');
    }

    public function index(Request $request)
    {
        $order = trim(strtolower($request->input('order')));
        if (($order != Config::get('constants.sortingStringComparison.listOrder.ascending')) 
            && ($order != Config::get('constants.sortingStringComparison.listOrder.descending')))
        {
            $order = Config::get('constants.sortingStringComparison.listOrder.ascending');
        }
        
        $this->GetFlashedMessages($request);
        $paginationStatusPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
        
        $statuses = new Status();
        $statuses = $statuses->orderBy('name', $order)->paginate($paginationStatusPerPageIndexCount);
        $statuses->appends(Input::except('page'));
        
        return View('statuses.index', array('statuses' => $statuses, 'list_order' => $order, 'messages' => $this->messages));
    }

    public function create(Request $request)
    {
        $this->authorize(Status::class);
        $this->GetFlashedMessages($request);
        $configurations = $this->GetConfiguration();
        return View('statuses.create', array('configurations' => $configurations, 'messages' => $this->messages));
    }

    public function store(StoreStatusRequest $request)
    {
        $status = new Status();
        return $this->InsertOrUpdate($request, $status, 'created', 'create');
    }

    public function show(Request $request, Status $status)
    {
        $this->GetFlashedMessages($request);
        $usageCount = $status->collections()->count();
        
        return View('statuses.show', array('status' => $status, 'usageCount' => $usageCount, 'messages' => $this->messages));
    }

    public function edit(Request $request, Status $status)
    {
        $this->authorize($status);
        $this->GetFlashedMessages($request);
        $configurations = $this->GetConfiguration();
        
        return View('statuses.edit', array('configurations' => $configurations, 'status' => $status, 'messages' => $this->messages));
    }

    public function update(UpdateStatusRequest $request, Status $status)
    {
        return $this->InsertOrUpdate($request, $status, 'updated', 'update');
    }

    public function destroy(Status $status)
    {
        $this->authorize($status);
        $statusName = $status->name;
        
        DB::beginTransaction();
        try
        {
            //Force deleting for now, build out functionality for soft deleting later.
            $status->forceDelete();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $this->AddWarningMessage("Unable to successfully delete status $statusName.", ['status' => $status->id, 'error' => $e]);
            return Redirect::back()->with(["messages" => $this->messages])->withInput();
        }
        DB::commit();
        
        $this->AddSuccessMessage("Successfully purged status $statusName from the database.");
        return redirect()->route('index_status')->with("messages", $this->messages);
    }

    private function InsertOrUpdate($request, $status, $action, $errorAction)
    {
        DB::beginTransaction();
        try
        {
            $status->fill($request->only(['name', 'priority']));    
            $status->save();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $this->AddWarningMessage("Unable to successfully $errorAction status $status->name.", ['status' => $status->id, 'error' => $e]);
            return Redirect::back()->with(["messages" => $this->messages])->withInput();
        }
        DB::commit();
        
        $this->AddSuccessMessage("Successfully $action status $status->name.");
        return redirect()->route('show_status', ['status' => $status])->with("messages", $this->messages);
    }
}
