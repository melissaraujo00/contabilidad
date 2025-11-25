<?php

namespace Database\Seeders;

use App\Models\CatalogoCuenta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogoCuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cuentas = [
            [
                'codigo' => '1',
                'cuenta' => 'ACTIVO',
                'cuenta_padre_id' => null,
            ],
            [
                'codigo' => '11',
                'cuenta' => 'ACTIVO CORRIENTE',
                'cuenta_padre_id' => 1,
            ],
            [
                'codigo' => '1101',
                'cuenta' => 'EFECTIVO Y EQUIVALENTES DE EFECTIVO',
                'cuenta_padre_id' => 2,
            ],
            [
                'codigo' => '110101',
                'cuenta' => 'Caja General',
                'cuenta_padre_id' => 3,
            ],
            [
                'codigo' => '110102',
                'cuenta' => 'Caja Chica',
                'cuenta_padre_id' => 3,
            ],
            [
                'codigo' => '110103',
                'cuenta' => 'Bancos',
                'cuenta_padre_id' => 3,
            ],
            [
                'codigo' => '11010301',
                'cuenta' => 'Cuenta Corriente',
                'cuenta_padre_id' => 6,
            ],
            [
                'codigo' => '11010302',
                'cuenta' => 'Cuenta Ahorro',
                'cuenta_padre_id' => 6,
            ],
            [
                'codigo' => '11010302',
                'cuenta' => 'Depósitos a Plazo',
                'cuenta_padre_id' => 6,
            ],
            [
                'codigo' => '1102',
                'cuenta' => 'INVERSIONES FINANCIERAS A CORTO PLAZO',
                'cuenta_padre_id' => 2,
            ],
            [
                'codigo' => '110201',
                'cuenta' => 'Depósitos a Plazo de 91 a 365 días',
                'cuenta_padre_id' => 10,
            ],
            [
                'codigo' => '110202',
                'cuenta' => 'Instrumentos Financieros a Corto Plazo',
                'cuenta_padre_id' => 10,
            ],
            [
                'codigo' => '110203',
                'cuenta' => 'Otros Títulos Valores',
                'cuenta_padre_id' => 10,
            ],
            [
                'codigo' => '1103',
                'cuenta' => 'UENTAS POR COBRAR',
                'cuenta_padre_id' => 2,
            ],
        ];

        foreach ($cuentas as &$cuenta) {
            $cuenta['is_active'] = 1;
            $cuenta['created_at'] = now();
            $cuenta['updated_at'] = now();
        }

        CatalogoCuenta::query()->insert($cuentas);
    }
}
