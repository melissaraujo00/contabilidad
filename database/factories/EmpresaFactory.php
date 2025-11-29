<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Enums\TipoEmpresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'tipo_empresa' => $this->faker->randomElement(
                array_column(TipoEmpresa::cases(), 'value')
            ),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
