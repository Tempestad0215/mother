<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //Crear los roles y permisos
        $this->call(RoleSeeder::class);

        // Crear el usuario
        $user = User::firstOrCreate(
            ['email' => 'marioguzman140@gmail.com'], // Condición para evitar duplicados
            [
                'name' => 'Marionil Guzman',
                'password' => bcrypt('password'), // Cambia 'password' por la contraseña deseada
            ]
        )->each(function ($user) {
            $user->assignRole('Super Admin');
        });

        // reset cached roles and permissions

        // Asignar el rol al usuarios para poder iniciar
//        $user->assignRole('Super Admin');
//p
//
//        //Crear los datos de pruebas
//        Category::factory(100)->create();
//        Client::factory(100)->create();
//        Product::factory(150)->create();
//        Setting::factory()->create();
//        Supplier::factory(25)->create();

    }


}
