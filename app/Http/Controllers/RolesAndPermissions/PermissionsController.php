<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RolesAndPermissions\Permissions\StorePermissionRequest;
use App\Http\Requests\RolesAndPermissions\Permissions\UpdatePermissionRequest;
use Auth;
use DB;
use Config;
use ConfigurationLookupHelper;

class PermissionsController extends WebController
{
	public function __construct()
    {
		parent::__construct();
		
		$this->paginationKey = "pagination_permissions_per_page_index";
		$this->placeholderStub = "permission";
		$this->placeheldFields = array('name');
		
		$this->middleware('auth');
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
	{
		$this->GetFlashedMessages($request);
		
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		
		$permissions = new Permission();
		$permissions = $permissions->orderby('id', 'asc')->paginate($paginationCount);
		
		return View('rolesAndPermissions.permissions.index', array('permissions' => $permissions, 'messages' => $this->messages));
	}
	
	public function create(Request $request)
    {
		$this->authorize(Permission::class);
		
		$this->GetFlashedMessages($request);
		$configuration = $this->GetConfiguration();
		
		return View('rolesAndPermissions.permissions.create', array('configuration' => $configuration, 'messages' => $this->messages));
	}
	
	public function store(StorePermissionRequest $request)
    {
		$permissionName = $request->input('name');
		DB::beginTransaction();
		try
		{
			Permission::create(['name' => $permissionName]);
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully create permission $permissionName.", ['error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully created new permission $permissionName.", true);
		return redirect()->route('admin_index_permission')->with("messages", $this->messages);
	}
	
	public function edit(Request $request, Permission $permission)
    {
		$this->authorize($permission);
		
		$this->GetFlashedMessages($request);
		$configuration = $this->GetConfiguration();
		
		return View('rolesAndPermissions.permissions.edit', array('permission' => $permission, 'configuration' => $configuration, 'messages' => $this->messages));
	}
	
	public function update(UpdatePermissionRequest $request, Permission $permission)
    {
		$permissionName = $request->input('name');
		
		DB::beginTransaction();
		try
		{
			$permission->name = $permissionName;
			$permission->save();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully update permission.", ['permission' => $permission->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully updated permission.", true, ['permission' => $permission->id]);
		return redirect()->route('admin_index_permission')->with("messages", $this->messages);
	}
	
	public function destroy(Permission $permission)
    {
		$this->authorize($permission);
		DB::beginTransaction();
		try
		{
			$permission->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully delete permission $permission->name.", ['permission' => $permission->id, 'error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully deleted permission $permission->name.", true, ['permission' => $permission->id]);
		return redirect()->route('admin_index_permission')->with("messages", $this->messages);
	}
}