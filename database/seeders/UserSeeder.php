<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_librarian_user = User::factory()->create([
            'first_name' => 'librarian',
            'last_name' => 'test',
            'email' => 'librarian@email.com',
        ]);

        $default_librarian_user->assignRole(Role::firstOrCreate(['name' => 'librarian']));
        $default_user = User::factory()->create([
            'first_name' => 'user',
            'last_name' => 'test',
            'email' => 'user@email.com',
        ]);
        $default_user->assignRole(Role::firstOrCreate(['name' => 'user']));

        User::factory(10)->create();
    }
}
