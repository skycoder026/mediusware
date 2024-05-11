<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // user for individual account
        User::firstOrCreate([
            'id' => 1
        ], [
            'name'              => 'Skycoder Individual',
            'account_type'      => 'Individual',
            'email'             => 'skycoder026-ndividual@gmail.com',
            'password'          => Hash::make('Individual12345678'),
            'email_verified_at' => now(),
        ]);


        // user for business account
        User::firstOrCreate([
            'id' => 2
        ], [
            'name'              => 'Skycoder Business',
            'account_type'      => 'Business',
            'email'             => 'skycoder026-business@gmail.com',
            'password'          => Hash::make('Business12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
