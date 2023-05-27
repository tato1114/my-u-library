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
        // User
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'user.register']));
        // Book
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.index']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.show']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.store']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.update']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.destroy']));
        // Check out
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'check_outs.index']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'check_outs.show']));
        $librarian_role->givePermissionTo(Permission::firstOrCreate(['name' => 'check_outs.update']));

        $user_role = Role::firstOrCreate(['name' => 'user']);
        // Book
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.index']));
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'books.show']));
        // Check out
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'check_outs.index']));
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'check_outs.show']));
        $user_role->givePermissionTo(Permission::firstOrCreate(['name' => 'check_outs.store']));
    }
}
