<?php

namespace Database\Seeders;


use App\Models\Category;
use App\Models\Client;
use App\Models\Role;
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



        // Crear el usuario
        $user = User::firstOrCreate(
            ['email' => 'marioguzman140@gmail.com'], // Condición para evitar duplicados
            [
                'name' => 'Marionil Guzman',
                'password' => bcrypt('password'), // Cambia 'password' por la contraseña deseada
            ]
        );



        //Crear los datos de pruebas
        Category::factory(15)->create();
        Client::factory(20)->create();
//        Product::factory(150)->create();
//        Setting::factory()->create();
        Supplier::factory(25)->create();

    }
}
