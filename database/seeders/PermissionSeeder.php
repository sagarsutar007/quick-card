<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view schools',
            'add school',
            'edit school',
            'delete school',
            'view school students',
            'view students',
            'upload student image',
            'view student image',
            'download student image',
            'remove student image',
            'manage student status',
            'delete student',
            'edit student',
            'add student',
            'import student',
            'manage multiple student statuses',
            'manage multiple student downloads',
            'add block',
            'edit block',
            'delete block',
            'view blocks',
            'view block details',
            'add cluster',
            'edit cluster',
            'delete cluster',
            'view cluster',
            'view clusters',
            'view cluster details',
            'add user',
            'edit user',
            'delete user',
            'view user',
            'view users',
            'view user details',
            'assign role',
            'assign permissions',
            'assign school',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }

        $authorityRole = Role::where('name', 'authority')->first();
        $permissions = Permission::whereIn('name', [
            'view schools', 
            'view school students', 
            'upload student image',
            'view student image',
            'download student image',
            'remove student image',
        ])->get();
        $authorityRole->givePermissionTo($permissions);

        $authorityRole = Role::where('name', 'staff')->first();
        $permissions = Permission::whereIn('name', [
            'view schools',
            'add school',
            'edit school',
            'delete school',
            'view school students',
            'view students',
            'upload student image',
            'view student image',
            'download student image',
            'remove student image',
            'manage student status',
            'delete student',
            'edit student',
            'add student',
            'import student',
            'manage multiple student statuses',
            'manage multiple student downloads',
        ])->get();
        $authorityRole->givePermissionTo($permissions);
    }
}