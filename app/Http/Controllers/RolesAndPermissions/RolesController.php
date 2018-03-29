<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RolesAndPermissions\Roles\StoreRoleRequest;
use App\Http\Requests\RolesAndPermissions\Roles\UpdateRoleRequest;
use Auth;
use DB;
use Config;
use Input;
use ConfigurationLookupHelper;

class RolesController extends WebController
{
	public function __construct()
    {
		$this->paginationKey = "pagination_roles_per_page_index";
		$this->placeholderStub = "role";
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
		$messages = self::GetFlashedMessages($request);
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($this->paginationKey)->value;
		
		$roles = new Role();
		$roles = $roles->orderby('id', 'asc')->paginate($paginationCount);
		
		return View('rolesAndPermissions.roles.index', array('roles' => $roles, 'messages' => $messages));
	}
	
	
	public function create(Request $request)
    {
		$this->authorize(Role::class);
		
		$messages = self::GetFlashedMessages($request);
		$configuration = $this->GetConfiguration();
		$permissions = new Permission();
		$permissions = $permissions->orderby('id', 'asc')->get(); //Update the permissions array to include some boolean that shows whether or not the user has it
		
		return View('rolesAndPermissions.roles.create', array('permissions' => $permissions, 'configuration' => $configuration, 'messages' => $messages));
	}
	
	public function store(StoreRoleRequest $request)
    {
		$role = null;
		
		$roleName = $request->input('name');
		DB::beginTransaction();
		try
		{
			$role = Role::create(['name' => $roleName]);
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully create role $roleName."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		DB::beginTransaction();
		try
		{
			$permissions = Permission::all();
			
			foreach($permissions as $permission)
			{
				//The string replace is due to spaces being replaced with underscores in the request
				if (Input::has(str_replace(" ", "_", $permission->name)))
				{
					$role->givePermissionTo($permission->name);
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully create role $roleName."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		$messages = self::BuildFlashedMessagesVariable(["Successfully created new role $roleName."], null, null);
		
		return redirect()->route('admin_show_role', ['role' => $role])->with("messages", $messages);
	}
	
	public function show(Request $request, Role $role)
	{
		$messages = self::GetFlashedMessages($request);
		return View('rolesAndPermissions.roles.show', array('role' => $role, 'messages' => $messages));
	}
	
	public function edit(Request $request, Role $role)
    {
		$this->authorize($role);
		
		$messages = self::GetFlashedMessages($request);
		$configuration = $this->GetConfiguration();
		
		$permissions = new Permission();
		$permissions = $permissions->orderby('id', 'asc')->get(); //Update the permissions array to include some boolean that shows
		
		foreach($permissions as &$permission)
		{
			$permission['hasValue'] = $role->permissions->contains('id', $permission->id);
		}
		
		return View('rolesAndPermissions.roles.edit', array('role' => $role, 'permissions' => $permissions, 'configuration' => $configuration, 'messages' => $messages));
	}
	
	public function update(UpdateRoleRequest $request, Role $role)
    {
		$roleName = $request->input('name');
		
		DB::beginTransaction();
		try
		{
			$role->name = $roleName;
			$role->save();
			
			$permissions = Permission::all();
			
			foreach($permissions as $permission)
			{
				//The string replace is due to spaces being replaced with underscores in the request
				if (Input::has(str_replace(" ", "_", $permission->name)) 
					&& (!($role->hasPermissionTo($permission->name))))
				{
					$role->givePermissionTo($permission->name);
				}
				//The string replace is due to spaces being replaced with underscores in the request
				else if ((!(Input::has(str_replace(" ", "_", $permission->name)))) 
					&& ($role->hasPermissionTo($permission->name)))
				{
					$role->revokePermissionTo($permission->name);
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully update role."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		$messages = self::BuildFlashedMessagesVariable(["Successfully updated role."], null, null);
		
		return redirect()->route('admin_show_role', ['role' => $role])->with("messages", $messages);
	}
	
	public function destroy(Role $role)
    {
		$this->authorize($role);
		DB::beginTransaction();
		try
		{
			$role->forceDelete();
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully delete role $role->name."]);
			return Redirect::back()->with(["messages" => $messages])->withInput();
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully deleted role $role->name."], null, null);
		
		return redirect()->route('admin_index_role')->with("messages", $messages);
	}
}