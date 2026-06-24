<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name'=>'admin',
            'email'=>'admin@gmail.com',
            'password'=>bcrypt('opwarnet09'),
            'role'=>'admin',
        ]);

        User::create([
            'name'=>'hosun',
            'email'=>'hosun@gmail.com',
            'password'=>bcrypt('opwarnet09'),
            'role'=>'kasir',
        ]);
    }
}
