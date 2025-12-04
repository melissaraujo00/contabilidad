<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\PeriodoFiscal;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\EmpresaFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CatalogoCuentaSeeder::class,
        ]);

    }
}
