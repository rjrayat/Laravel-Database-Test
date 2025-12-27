<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'mobile'=>'01234567890',
            'email' => 'rjrayat37@gmail.com',
            'otp' => null, // 👈 add this
            'password' => Hash::make('11111')
            

        ]);
    }
}
