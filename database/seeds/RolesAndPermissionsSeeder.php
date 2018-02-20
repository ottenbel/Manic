<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
	{
    	// Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
		self::SeedPermissionRow('Export Collection');
        self::SeedPermissionRow('Export Volume');
		self::SeedPermissionRow('Export Chapter');
		self::SeedPermissionRow('Create Personal Artist Alias');
		self::SeedPermissionRow('Delete Personal Artist Alias');
		self::SeedPermissionRow('Create Personal Character Alias');
		self::SeedPermissionRow('Delete Personal Character Alias');
		self::SeedPermissionRow('Create Personal Scanalator Alias');
		self::SeedPermissionRow('Delete Personal Scanalator Alias');
		self::SeedPermissionRow('Create Personal Series Alias');
		self::SeedPermissionRow('Delete Personal Series Alias');
		self::SeedPermissionRow('Create Personal Tag Alias');
		self::SeedPermissionRow('Delete Personal Tag Alias');
		self::SeedPermissionRow('Edit Personal Pagination Settings');
		self::SeedPermissionRow('Edit Personal Rating Restriction Settings');
		self::SeedPermissionRow('Edit Personal Rating Restriction Settings');
		self::SeedPermissionRow('Edit Personal Rating Restriction Settings');
		self::SeedPermissionRow('Add Favourite Collection');
		self::SeedPermissionRow('Delete Favourite Collection');
		self::SeedPermissionRow('Create Collection');
		self::SeedPermissionRow('Edit Collection');
		self::SeedPermissionRow('Delete Collection');
		self::SeedPermissionRow('Create Volume');
		self::SeedPermissionRow('Edit Volume');
		self::SeedPermissionRow('Delete Volume');
		self::SeedPermissionRow('Create Chapter');
		self::SeedPermissionRow('Edit Chapter');
		self::SeedPermissionRow('Delete Chapter');
		self::SeedPermissionRow('Create Artist');
		self::SeedPermissionRow('Edit Artist');
		self::SeedPermissionRow('Delete Artist');
		self::SeedPermissionRow('Create Global Artist Alias');
		self::SeedPermissionRow('Delete Global Artist Alias');
		self::SeedPermissionRow('Create Character');
		self::SeedPermissionRow('Edit Character');
		self::SeedPermissionRow('Delete Character');
		self::SeedPermissionRow('Create Global Character Alias');
		self::SeedPermissionRow('Delete Global Character Alias');
		self::SeedPermissionRow('Create Scanalator');
		self::SeedPermissionRow('Edit Scanalator');
		self::SeedPermissionRow('Delete Scanalator');
		self::SeedPermissionRow('Create Global Scanalator Alias');
		self::SeedPermissionRow('Delete Global Scanalator Alias');
		self::SeedPermissionRow('Create Series');
		self::SeedPermissionRow('Edit Series');
		self::SeedPermissionRow('Delete Series');
		self::SeedPermissionRow('Create Global Series Alias');
		self::SeedPermissionRow('Delete Global Series Alias');
		self::SeedPermissionRow('Create Tag');
		self::SeedPermissionRow('Edit Tag');
		self::SeedPermissionRow('Delete Tag');
		self::SeedPermissionRow('Create Global Tag Alias');
		self::SeedPermissionRow('Delete Global Tag Alias');
		self::SeedPermissionRow('Edit Personal Placeholder Settings');
		self::SeedPermissionRow('Edit Global Pagination Settings');
		self::SeedPermissionRow('Edit Global Placeholder Settings');
		self::SeedPermissionRow('Edit Global Rating Restriction Settings');
		self::SeedPermissionRow('Create Language');
		self::SeedPermissionRow('Edit Language');
		self::SeedPermissionRow('Delete Language');
		self::SeedPermissionRow('Create Status');
		self::SeedPermissionRow('Edit Status');
		self::SeedPermissionRow('Delete Status');
		self::SeedPermissionRow('Create Rating');
		self::SeedPermissionRow('Edit Rating');
		self::SeedPermissionRow('Delete Rating');
		self::SeedPermissionRow('View User');
		self::SeedPermissionRow('View User Index');
		self::SeedPermissionRow('Create Role');
		self::SeedPermissionRow('Edit Role');
		self::SeedPermissionRow('Delete Role');
		self::SeedPermissionRow('Create Permission');
		self::SeedPermissionRow('Edit Permission');
		self::SeedPermissionRow('Delete Permission');
		self::SeedPermissionRow('Edit User Roles and Permissions');
		
        // create roles and assign existing permissions
		self::SeedRoleAndPermissions('user', 
		[
			'Export Collection', 'Export Volume', 'Export Chapter', 'Create Personal Artist Alias', 
			'Delete Personal Artist Alias', 'Create Personal Character Alias', 'Delete Personal Character Alias', 
			'Create Personal Scanalator Alias', 'Delete Personal Scanalator Alias', 'Create Personal Series Alias', 
			'Delete Personal Series Alias', 'Create Personal Tag Alias', 'Delete Personal Tag Alias', 
			'Edit Personal Pagination Settings', 'Edit Personal Rating Restriction Settings', 'Add Favourite Collection',
			'Delete Favourite Collection'
		]);
		
		self::SeedRoleAndPermissions('editor', 
		[
			'Create Collection', 'Edit Collection', 'Delete Collection', 'Create Volume', 'Edit Volume', 'Delete Volume', 'Create Chapter', 'Edit Chapter', 'Delete Chapter', 'Create Artist', 'Edit Artist', 'Delete Artist', 
			'Create Global Artist Alias', 'Delete Global Artist Alias', 'Create Character', 'Edit Character', 
			'Delete Character', 'Create Global Character Alias', 'Delete Global Character Alias', 'Create Scanalator', 
			'Edit Scanalator', 'Delete Scanalator', 'Create Global Scanalator Alias', 'Delete Global Scanalator Alias', 'Create Series', 'Edit Series', 'Delete Series', 'Create Global Series Alias', 'Delete Global Series Alias', 'Create Tag', 'Edit Tag', 'Delete Tag', 'Create Global Tag Alias', 'Delete Global Tag Alias', 
			'Edit Personal Placeholder Settings'
		]);
		
		self::SeedRoleAndPermissions('administrator', 
		[
			'Create Language', 'Edit Language', 'Delete Language', 'Create Status', 'Edit Status', 'Delete Status', 
			'Create Rating', 'Edit Rating', 'Delete Rating', 'Edit Global Pagination Settings', 
			'Edit Global Placeholder Settings', 'Edit Global Rating Restriction Settings', 'View User', 'View User Index'
		]);
		
		self::SeedRoleAndPermissions('owner', 
		[
			'Create Role', 'Edit Role', 'Delete Role', 'Create Permission', 'Edit Permission', 'Delete Permission', 
			'Edit User Roles and Permissions'
		]);
    }
	
	private static function SeedPermissionRow($name)
	{
		$permission = Permission::where('name', '=', $name)->first();
		if ($permission == null)
		{
			Permission::create(['name' => $name]);
		}
	}
	
	private static function SeedRoleAndPermissions($name, $permissions)
	{
		$role = Role::where('name', '=', $name)->first();
		if ($role == null)
		{
			$role = Role::create(['name' => $name]);
			foreach($permissions as $permission)
			{
				$per = Permission::where('name', '=', $permission)->first();
				if ($per != null)
				{
					$role->givePermissionTo($permission);
				}
			}
		}
	}
}
