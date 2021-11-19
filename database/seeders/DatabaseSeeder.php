<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use DB;
use Hash;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //create role
        DB::table('role')->insert([

            [
                'name' => 'Super Admin',
                'description' => 'Le Super de Admin',
            ],
            [
                'name' => 'Admin',
                'description' => 'Administrator Account',
            ],
            [
                'name' => 'Company',
                'description' => 'Company Account',
            ],
            [
                'name' => 'Employee',
                'description' => 'Employee Account',
            ]
        
        ]);
        //create super admin user

        DB::table('user')->insert([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'password' => Hash::make('password'),
            'email' => 'superadmin@admin.com',
            'role_id' => 1,
            'access_token' => Str::random(10),
            'created_by' => 1
        ]);
    }
}
