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
        // To clean insert all permissions run this command
        // php artisan db:seed --class=PermissionSeeder

        $librarian_role = Role::firstOrCreate(['name' => 'librarian']);
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'user.register']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.index']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.show']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.store']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.update']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.destroy']));

        $user_role = Role::firstOrCreate(['name' => 'user']);
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.index']));
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.show']));
    }
}
