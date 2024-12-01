<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $homeEmployee = User::create([
            'id' => (string) Str::uuid(), // Generate UUID
            'name' => 'P4nggungMJS',
            'username' => 'P4nggungMJS',
            'email' => 'P4nggungMJS@majusari.com',
            'password' => Hash::make('P3g4w41MJS'),
            'status' => 1,
            'email_verified_at' => Carbon::now()
        ]);
        $homeEmployee->assignRole('home_employee');

        $storeEmployee = User::create([
            'id' => (string) Str::uuid(), // Generate UUID
            'name' => 'MJStuamuda',
            'username' => 'MJStuamuda',
            'email' => 'MJStuamuda@majusari.com',
            'password' => Hash::make('Empl0y33MJS'),
            'status' => 1,
            'email_verified_at' => Carbon::now()
        ]);
        $storeEmployee->assignRole('store_employee');

        $this->command->info('Users with role have been seeded successfully.');
    }
}