<?php

namespace Database\Seeders;

use App\Models\TipoCuenta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Symfony\Component\Clock\now;

class TipoCuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cuentas = [
            [
                'name' => 'ACTIVO'
            ],
            [
                'name' => 'PASIVO'
            ],
            [
                'name' => 'PATRIMONIO'
            ],
            [
                'name' => 'CUENTAS DE RESULTADOS DEUDORAS'
            ],
            [
                'name' => 'CUENTAS DE RESULTADOS ACREEDORAS'
            ],
            [
                'name' => 'CUENTA DE CIERRE'
            ]
        ];

        foreach ($cuentas as &$cuenta) {
            $cuenta['created_at'] = now();
            $cuenta['updated_at'] = now();
        }

        TipoCuenta::query()->insert($cuentas);
    }
}
