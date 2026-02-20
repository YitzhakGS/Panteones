<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatTipoEspacioFisicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Area',
            'Manzana',
            'Gaveta',
            'Barda', 
        ];

        foreach ($tipos as $tipo) {
            DB::table('cat_tipos_espacio_fisico')->updateOrInsert([
                'nombre' => $tipo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
