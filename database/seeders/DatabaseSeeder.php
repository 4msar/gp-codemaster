<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( User::where('email', 'admin@admin.com')->count() <= 0){
            User::create([
                'name' => "Admin",
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);
        }

        $this->call(CustomersTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
    }
}
