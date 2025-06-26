<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Nirmal Behera',
            'password' => bcrypt('test1234'), 
            'phone' => '8847852505'
        ]);

        // Assign role if using Spatie
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin');
        }
    }
}
