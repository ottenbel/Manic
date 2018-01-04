<?php

namespace App\Http\Controllers\RolesAndPermissions;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use App\Http\Requests\RolesAndPermissions\Roles\StoreRoleRequest;
use App\Http\Requests\RolesAndPermissions\Roles\UpdateRoleRequest;
use Auth;
use DB;
use Config;
use ConfigurationLookupHelper;

class RolesController extends WebController
{
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
	{
		$messages = self::GetFlashedMessages($request);
		$lookupKey = Config::get('constants.keys.pagination.rolesPerPageIndex');
		$paginationCount = ConfigurationLookupHelper::LookupPaginationConfiguration($lookupKey)->value;
		
		$roles = new Role();
		$roles = $roles->orderby('id', 'asc')->paginate($paginationCount);
		
		return View('rolesAndPermissions.roles.index', array('roles' => $roles, 'messages' => $messages));
	}
	
	
	public function create(Request $request)
    {
		
	}
	
	public function store(StoreRoleRequest $request)
    {
		
	}
	
	public function show(Request $request, Role $role)
	{
		$messages = self::GetFlashedMessages($request);
		return View('rolesAndPermissions.roles.show', array('role' => $role, 'messages' => $messages));
	}
	
	public function edit(Request $request, Role $role)
    {
		
	}
	
	public function update(UpdateRoleRequest $request, Role $role)
    {
		
	}
	
	public function destroy(Role $role)
    {
		
	}
}