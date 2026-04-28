<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\CatEstatus;

class CatEstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estatus = [
            [
                'nombre'      => 'Activa',
                'descripcion' => 'Estado inicial administrativo al crear la concesión.',
            ],
            [
                'nombre'      => 'Cancelada',
                'descripcion' => 'Concesión anulada por falta de pago prolongada o decisión administrativa.',
            ],
        ];

        foreach ($estatus as $item) {
            DB::table('cat_estatus')->updateOrInsert(
                ['nombre' => $item['nombre']], // Evita duplicados si corres el seeder varias veces
                ['descripcion' => $item['descripcion']]
            );
        }
    }
}
