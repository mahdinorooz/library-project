<?php

namespace Database\Seeders;

use App\Models\BackOfficeUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;

class PermissionSeeder extends Seeder
{
    use HasRoles;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'column-row-list']);
        Permission::create(['name' => 'column-row']);
        Permission::create(['name' => 'type-list']);
        Permission::create(['name' => 'type-create']);
        $role = Role::create(['name' => 'Super-Admin']);
        $role->givePermissionTo('column-row-list');
        $role->givePermissionTo('column-row');
        $role->givePermissionTo('type-list');
        $role->givePermissionTo('type-create');
        $backOfficeUser = BackOfficeUser::create([
            'first_name' => 'نسرین',
            'last_name' => 'بابایی',
            'national_code' => '2981232211',
            'mobile' => '09935044559',
            'address' => 'کرمان خیابان شفا',
            'degree' => 'لیسانس',
            'email' => 'nasrin81@yahoo.com',
            'password' => '98355154',
        ]);
        $backOfficeUser->assignRole($role);
        // $backOfficeUser->givePermissionTo('reserved-books');
    }
}
