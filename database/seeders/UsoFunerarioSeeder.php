<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class UsoFunerarioSeeder extends Seeder
{
    public function run(): void
    {
        $usos = [
            'Fosa individual',
            'Fosa doble',
            'Fosa triple',
            'Gaveta',
            'Osario',
            'Cenizario',
            'Cripta familiar',
            'Tumba vertical',
        ];

        foreach ($usos as $uso) {
            DB::table('uso_funerario')->insert([
                'nombre' => $uso,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
