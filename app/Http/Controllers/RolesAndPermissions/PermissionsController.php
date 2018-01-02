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
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
	{
		$messages = self::GetFlashedMessages($request);
		$lookupKey = Config::get('constants.keys.pagination.permissionsPerPageIndex');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$permissions = new Permission();
		$permissions = $permissions->orderby('id', 'asc')->paginate($paginationCount);
		
		return View('rolesAndPermissions.permissions.index', array('permissions' => $permissions, 'messages' => $messages));
	}
	
	public function create(Request $request)
    {
		$this->authorize(Permission::class);
		$messages = self::GetFlashedMessages($request);
		$configuration = Auth::user()->placeholder_configuration()->where('key', 'like', 'permission%')->first();
		return View('rolesAndPermissions.permissions.create', array('configuration' => $configuration, 'messages' => $messages));
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully create permission $permissionName."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully created new permission $permissionName."], null, null);
		
		return redirect()->route('index_permission')->with("messages", $messages);
	}
	
	public function edit(Request $request, Permission $permission)
    {
		$this->authorize($permission);
		$messages = self::GetFlashedMessages($request);
		$configuration = Auth::user()->placeholder_configuration()->where('key', 'like', 'permission%')->first();
		return View('rolesAndPermissions.permissions.edit', array('permission' => $permission, 'configuration' => $configuration, 'messages' => $messages));
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully update permission."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully updated permission."], null, null);
		
		return redirect()->route('index_permission')->with("messages", $messages);
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
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully delete permission $permission->name."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully deleted permission $permission->name."], null, null);
		
		return redirect()->route('index_permission')->with("messages", $messages);
	}
}