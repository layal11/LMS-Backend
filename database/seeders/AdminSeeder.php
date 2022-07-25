<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = Admin::create([
            'name' => 'admin',
            'last_name' => 'pro',
            'email' => 'admin@gmail.com',
            'password' => 'Admin!2021',
        ]);
    }
}
