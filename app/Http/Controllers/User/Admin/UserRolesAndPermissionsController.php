<?php

namespace App\Http\Controllers\User\Admin;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\User\Admin\User\RolesAndPermissions\UpdateRolesAndPermissionsRequest;
use App\Models\User;
use Auth;
use DB;
use Config;
use Input;

class UserRolesAndPermissionsController extends WebController
{
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware(['auth', 'permission:Edit User Roles and Permissions']);
	}
	
	public function edit(Request $request, User $user)
    {
		$this->GetFlashedMessages($request);
		
		$roles = new Role();
		$roles = $roles->orderby('id', 'asc')->get(); 
		
		foreach($roles as &$role)
		{
			$role['hasValue'] = $user->roles->contains('id', $role->id);
		}
		
		$permissions = new Permission();
		$permissions = $permissions->orderby('id', 'asc')->get(); 
		
		foreach($permissions as &$permission)
		{
			$permission['hasValue'] = $user->permissions->contains('id', $permission->id);
		}
		
		return View('user.admin.user.rolesAndPermissions.edit', array('user' => $user, 'roles' => $roles, 'permissions' => $permissions, 'messages' => $this->messages));
	}
	
	public function update(UpdateRolesAndPermissionsRequest $request, User $user)
    {		
		DB::beginTransaction();
		try
		{
			//handle updating roles
			$roles = Role::all();
			
			foreach($roles as $role)
			{
				//The string replace is due to spaces being replaced with underscores in the request
				if (Input::has("role-".str_replace(" ", "_", $role->name)) 
					&& (!($user->hasRole($role->name))))
				{
					$user->assignRole($role->name);
				}
				//The string replace is due to spaces being replaced with underscores in the request
				else if ((!(Input::has("role-".str_replace(" ", "_", $role->name)))) 
					&& ($user->hasRole($role->name)))
				{
					$user->removeRole($role->name);
				}
			}
			
			//handle updating permissions
			$permissions = Permission::all();
			
			foreach($permissions as $permission)
			{
				//The string replace is due to spaces being replaced with underscores in the request
				if (Input::has("permission-".str_replace(" ", "_", $permission->name)) 
					&& (!($user->hasDirectPermission($permission->name))))
				{
					$user->givePermissionTo($permission->name);
				}
				//The string replace is due to spaces being replaced with underscores in the request
				else if ((!(Input::has("permission-".str_replace(" ", "_", $permission->name)))) 
					&& ($user->hasDirectPermission($permission->name)))
				{
					$user->revokePermissionTo($permission->name);
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully update roles and permissions for user $user->name.", ['error' => $e]);
			return Redirect::back()->with(["messages" => $this->messages])->withInput();
		}
		DB::commit();
		
		$this->AddSuccessMessage("Successfully updated user roles and permissions.", true);
		return redirect()->route('admin_show_user', ['user' => $user])->with("messages", $this->messages);
	}
}