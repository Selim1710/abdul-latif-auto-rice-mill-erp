<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =  [
                [
                    'role_id'  => 1,
                    'name'     => 'Super Admin',
                    'username' => 'SuperAdmin',
                    'email'    => 'superadmin@mail.com',
                    'phone'    => '01521225987',
                    'gender'   => '1',
                    'password' => 'SuperAdmin@100%',
                    'deletable'=> 1
                ],
                [
                    'role_id'  => 2,
                    'name'     => 'Admin',
                    'username' => 'Admin',
                    'email'    => 'admin@mail.com',
                    'phone'    => '0123456789',
                    'gender'   => '1',
                    'password' => 'Admin@100%',
                    'deletable'=> 1
                ]
            ];
        foreach ($users as $value) {
            User::create($value);
        }
        
    }
}
