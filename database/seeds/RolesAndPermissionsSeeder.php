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
        Permission::create(['name' => 'Export Collection']);
		Permission::create(['name' => 'Export Volume']);
		Permission::create(['name' => 'Export Chapter']);
		Permission::create(['name' => 'Create Personal Artist Alias']);
		Permission::create(['name' => 'Delete Personal Artist Alias']);
		Permission::create(['name' => 'Create Personal Character Alias']);
		Permission::create(['name' => 'Delete Personal Character Alias']);
		Permission::create(['name' => 'Create Personal Scanalator Alias']);
		Permission::create(['name' => 'Delete Personal Scanalator Alias']);
		Permission::create(['name' => 'Create Personal Series Alias']);
		Permission::create(['name' => 'Delete Personal Series Alias']);
		Permission::create(['name' => 'Create Personal Tag Alias']);
		Permission::create(['name' => 'Delete Personal Tag Alias']);
		Permission::create(['name' => 'Edit Personal Pagination Settings']);
		Permission::create(['name' => 'Edit Personal Rating Restriction Settings']);
		Permission::create(['name' => 'Create Collection']);
		Permission::create(['name' => 'Edit Collection']);
		Permission::create(['name' => 'Delete Collection']);
		Permission::create(['name' => 'Create Volume']);
		Permission::create(['name' => 'Edit Volume']);
		Permission::create(['name' => 'Delete Volume']);
		Permission::create(['name' => 'Create Chapter']);
		Permission::create(['name' => 'Edit Chapter']);
		Permission::create(['name' => 'Delete Chapter']);
		Permission::create(['name' => 'Create Artist']);
		Permission::create(['name' => 'Edit Artist']);
		Permission::create(['name' => 'Delete Artist']);
		Permission::create(['name' => 'Create Global Artist Alias']);
		Permission::create(['name' => 'Delete Global Artist Alias']);
		Permission::create(['name' => 'Create Character']);
		Permission::create(['name' => 'Edit Character']);
		Permission::create(['name' => 'Delete Character']);
		Permission::create(['name' => 'Create Global Character Alias']);
		Permission::create(['name' => 'Delete Global Character Alias']);
		Permission::create(['name' => 'Create Scanalator']);
		Permission::create(['name' => 'Edit Scanalator']);
		Permission::create(['name' => 'Delete Scanalator']);
		Permission::create(['name' => 'Create Global Scanalator Alias']);
		Permission::create(['name' => 'Delete Global Scanalator Alias']);
		Permission::create(['name' => 'Create Series']);
		Permission::create(['name' => 'Edit Series']);
		Permission::create(['name' => 'Delete Series']);
		Permission::create(['name' => 'Create Global Series Alias']);
		Permission::create(['name' => 'Delete Global Series Alias']);
		Permission::create(['name' => 'Create Tag']);
		Permission::create(['name' => 'Edit Tag']);
		Permission::create(['name' => 'Delete Tag']);
		Permission::create(['name' => 'Create Global Tag Alias']);
		Permission::create(['name' => 'Delete Global Tag Alias']);
		Permission::create(['name' => 'Edit Personal Placeholder Settings']);
		Permission::create(['name' => 'Edit Global Pagination Settings']);
		Permission::create(['name' => 'Edit Global Placeholder Settings']);
		Permission::create(['name' => 'Edit Global Rating Restriction Settings']);
		Permission::create(['name' => 'Modify User Level Account Permissions and Roles']);
		Permission::create(['name' => 'Modify Editor Level Account Permissions and Roles']);
		Permission::create(['name' => 'Assign User Level Permissions and Roles To Account To Account']);
		Permission::create(['name' => 'Assign Editor Level Permissions and Roles To Account']);
		Permission::create(['name' => 'Remove User Level Permissions and Roles From Account']);
		Permission::create(['name' => 'Remove Editor Level Permissions and Roles From Account']);
		Permission::create(['name' => 'Modify Administrator Level Account Permissions and Roles']);
		Permission::create(['name' => 'Modify Owner Level Account Permissions and Roles']);
		Permission::create(['name' => 'Assign Administrator Level Permissions and Roles']);
		Permission::create(['name' => 'Assign Owner Level Permissions and Roles']);
		Permission::create(['name' => 'Remove Administrator Level Permissions and Roles']);
		Permission::create(['name' => 'Remove Owner Level Permissions and Roles']);
		Permission::create(['name' => 'Create Role']); 
		Permission::create(['name' => 'Edit Role']);
		Permission::create(['name' => 'Delete Role']);
		Permission::create(['name' => 'Create Permission']);
		Permission::create(['name' => 'Edit Permission']);
		Permission::create(['name' => 'Delete Permission']);
		
        // create roles and assign existing permissions
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo('Export Collection');
		$role->givePermissionTo('Export Volume');
		$role->givePermissionTo('Export Chapter');
		$role->givePermissionTo('Create Personal Artist Alias');
		$role->givePermissionTo('Delete Personal Artist Alias');
		$role->givePermissionTo('Create Personal Character Alias');
		$role->givePermissionTo('Delete Personal Character Alias');
		$role->givePermissionTo('Create Personal Scanalator Alias');
		$role->givePermissionTo('Delete Personal Scanalator Alias');
		$role->givePermissionTo('Create Personal Series Alias');
		$role->givePermissionTo('Delete Personal Series Alias');
		$role->givePermissionTo('Create Personal Tag Alias');
		$role->givePermissionTo('Delete Personal Tag Alias');
		$role->givePermissionTo('Edit Personal Pagination Settings');
		$role->givePermissionTo('Edit Personal Rating Restriction Settings');
		
		$role = Role::create(['name' => 'editor']);
		$role->givePermissionTo('Create Collection');
		$role->givePermissionTo('Edit Collection');
		$role->givePermissionTo('Delete Collection');
		$role->givePermissionTo('Create Volume');
		$role->givePermissionTo('Edit Volume');
		$role->givePermissionTo('Delete Volume');
		$role->givePermissionTo('Create Chapter');
		$role->givePermissionTo('Edit Chapter');
		$role->givePermissionTo('Delete Chapter');
		$role->givePermissionTo('Create Artist');
		$role->givePermissionTo('Edit Artist');
		$role->givePermissionTo('Delete Artist');
		$role->givePermissionTo('Create Global Artist Alias');
		$role->givePermissionTo('Delete Global Artist Alias');
		$role->givePermissionTo('Create Character');
		$role->givePermissionTo('Edit Character');
		$role->givePermissionTo('Delete Character');
		$role->givePermissionTo('Create Global Character Alias');
		$role->givePermissionTo('Delete Global Character Alias');
		$role->givePermissionTo('Create Scanalator');
		$role->givePermissionTo('Edit Scanalator');
		$role->givePermissionTo('Delete Scanalator');
		$role->givePermissionTo('Create Global Scanalator Alias');
		$role->givePermissionTo('Delete Global Scanalator Alias');
		$role->givePermissionTo('Create Series');
		$role->givePermissionTo('Edit Series');
		$role->givePermissionTo('Delete Series');
		$role->givePermissionTo('Create Global Series Alias');
		$role->givePermissionTo('Delete Global Series Alias');
		$role->givePermissionTo('Create Tag');
		$role->givePermissionTo('Edit Tag');
		$role->givePermissionTo('Delete Tag');
		$role->givePermissionTo('Create Global Tag Alias');
		$role->givePermissionTo('Delete Global Tag Alias');
		$role->givePermissionTo('Edit Personal Placeholder Settings');
		
		$role = Role::create(['name' => 'administrator']);
		$role->givePermissionTo('Edit Global Pagination Settings');
		$role->givePermissionTo('Edit Global Placeholder Settings');
		$role->givePermissionTo('Edit Global Rating Restriction Settings');
		$role->givePermissionTo('Modify User Level Account Permissions and Roles');
		$role->givePermissionTo('Modify Editor Level Account Permissions and Roles');
		$role->givePermissionTo('Assign User Level Permissions and Roles To Account To Account');
		$role->givePermissionTo('Assign Editor Level Permissions and Roles To Account');
		$role->givePermissionTo('Remove User Level Permissions and Roles From Account');
		$role->givePermissionTo('Remove Editor Level Permissions and Roles From Account');
		
		$role = Role::create(['name' => 'owner']);
		$role->givePermissionTo('Modify Administrator Level Account Permissions and Roles');
		$role->givePermissionTo('Modify Owner Level Account Permissions and Roles');
		$role->givePermissionTo('Assign Administrator Level Permissions and Roles');
		$role->givePermissionTo('Assign Owner Level Permissions and Roles');
		$role->givePermissionTo('Remove Administrator Level Permissions and Roles');
		$role->givePermissionTo('Remove Owner Level Permissions and Roles');
		$role->givePermissionTo('Create Role');
		$role->givePermissionTo('Edit Role');
		$role->givePermissionTo('Delete Role');
		$role->givePermissionTo('Create Permission');
		$role->givePermissionTo('Edit Permission');
		$role->givePermissionTo('Delete Permission');
    }
}
