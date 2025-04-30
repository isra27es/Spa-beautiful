<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol1 = new Roles;
        $rol1->roles_id = 1;
        $rol1->role_name = "Admin";
        $rol1->save();

        $rol2 = new Roles;
        $rol2->roles_id = 2;
        $rol2->role_name = "User";
        $rol2->save();
    }
}
