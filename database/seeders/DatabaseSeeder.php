<?php

namespace Database\Seeders;


use App\Models\Category;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);

        //Crear el usuario
        $user = User::factory()->create([
            'name' => 'Marionil Guzman',
            'email' => 'marioguzman140@gmail.com',
        ]);



        Category::factory(15)->create();
        Client::factory(20)->create();
//        Product::factory(150)->create();
//        Setting::factory()->create();
        Supplier::factory(25)->create();

    }
}
