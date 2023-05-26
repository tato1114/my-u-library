<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_role = Role::create(['name' => 'user']);
        $librarian_role = Role::create(['name' => 'librarian']);
        $librarian_role->givePermissionTo(Permission::create(['name' => 'user.register']));
    }
}
