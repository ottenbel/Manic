<?php

namespace App\Http\Controllers\User\Admin;

use App\Http\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RolesAndPermissions\Roles\StoreRoleRequest;
use App\Http\Requests\RolesAndPermissions\Roles\UpdateRoleRequest;
use User;
use Auth;
use DB;
use Config;
use Input;
use ConfigurationLookupHelper;

class UserRolesAndPermissionsController extends WebController
{
	public function edit(Request $request, User $user)
    {
		
	}
	
	public function update(UpdateRoleRequest $request, User $user)
    {
		
	}
}