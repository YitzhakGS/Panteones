<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rol')->insert([
            ['name' => 'SuperAdmin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'User', 'created_at' => now(), 'updated_at' => now()],
            
        ]);
        $superAdmin = User::create([
            'name' => 'Pruebas',
            'email' => 'd@d',
            'sexo' => 'otro',
            'password' => Hash::make('12345'),
            'rol' => '1',
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Pruebas',
            'email' => 'b@b',
            'sexo' => 'Hombre',
            'password' => Hash::make('12345'),
            'rol' => '2',
        ]);
        $admin->assignRole('Admin');
        $admin = User::create([
            'name' => 'Pruebas',
            'email' => 'f@f',
            'sexo' => '
            Mujer',
            'password' => Hash::make('12345'),
            'rol' => '3',
        ]);
        $admin->assignRole('Admin');

        //        // Creating Product Manager User
        //        $productManager = User::create([
        //            'name' => 'S',
        //            'email' => 'S@S',
        //            'password' => Hash::make('12345'),
        //            'rol'=>'3',
        //        ]);
        //        $productManager->assignRole(' Soporte');

        //        $productManager1 = User::create([
        //         'name' => 'R',
        //         'email' => 'R@R',
        //         'password' => Hash::make('12345'),
        //         'rol'=>'4',
        //     ]);
        //     $productManager1->assignRole(' Redes');

        //     $productManager1 = User::create([
        //         'name' => 'DD',
        //         'email' => 'DD@DD',
        //         'password' => Hash::make('12345'),
        //         'rol'=>'5',
        //     ]);
        //     $productManager1->assignRole('Desarrollo');
        //
    }
}
