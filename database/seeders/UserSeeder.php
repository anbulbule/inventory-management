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
            'name'=>'admin',
            'mobile'=>'8553514403',
            'email'=>'admin@gmail.com',
            'city'=>'belgaum',
            'password'=>Hash::make('admin'),
            'user_type'=>'admin'
        ]);
    }
}
