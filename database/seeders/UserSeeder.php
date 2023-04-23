<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'MKevyn HH',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
        ]);

        /***************************************
         * Creamos los usuarios con el factory *
         ***************************************/
        User::factory(99)->create();
    }
}
