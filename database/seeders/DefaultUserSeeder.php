<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * php artisan db:seed --class=DefaultUserSeeder
     */
    public function run(): void
    {
        // ADMIN USER
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@admin.com'
            ],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('admin');

        // NORMAL USER
        $user = User::firstOrCreate(
            [
                'email' => 'user@user.com'
            ],
            [
                'name' => 'Normal User',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('user');
    }
}
