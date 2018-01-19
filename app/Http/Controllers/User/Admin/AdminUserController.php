<?php

namespace App\Http\Controllers\User\Admin;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RolesAndPermissions\Roles\StoreRoleRequest;
use App\Http\Requests\RolesAndPermissions\Roles\UpdateRoleRequest;
use App\Models\User;
use Auth;
use DB;
use Config;
use Input;
use ConfigurationLookupHelper;

class AdminUserController extends WebController
{
	public function __construct()
	{
		$this->middleware('auth');
		
		$this->middleware('permission:View User Index')->only('index');
		$this->middleware('permission:View User')->only('show');
	}
	
	public function index(Request $request)
    {
		$messages = self::GetFlashedMessages($request);
		$lookupKey = Config::get('constants.keys.pagination.usersPerPageIndex');
		$paginationUsersPerPageIndexCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
			
		$users = new User();
		$users = $users->orderBy('name', 'asc')->paginate($paginationUsersPerPageIndexCount);
		
		return View('user.admin.user.index', array('users' => $users, 'messages' => $messages));
	}
	
	public function show(Request $request, User $user)
    {	
		$messages = self::GetFlashedMessages($request);
		$roles = $user->roles;
		$permissions = $user->permissions;
		
		return View ('user.admin.user.show', array('user' => $user, 'roles' => $roles, 'permissions' => $permissions, 'messages' => $messages));
	}
}