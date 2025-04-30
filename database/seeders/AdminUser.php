<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = 'Administrador';
        $user->lastname = 'Administrador';
        $user->number_phone = 7751621020;
        $user->year_birth = '2000';
        $user->email = 'admin@admin.com';
        $user->password = Hash::make('admin123');
        $user->roles_id = 1;
        $user->save();
    }
}
