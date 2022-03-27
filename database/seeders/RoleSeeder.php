<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => Role::IS_ADMIN,
            'name' => 'Admin'
        ]);
        Role::create([
            'id' => Role::IS_USER,
            'name' => 'User'
        ]);
    }
}
