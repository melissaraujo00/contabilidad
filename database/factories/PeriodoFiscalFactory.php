<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeriodoFiscal>
 */
class PeriodoFiscalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $empresas = Empresa::pluck('id')->toArray();

        $fechaInicio = $this->faker->dateTimeBetween('-2 years', 'now');
        $fechaCierre = $this->faker->dateTimeBetween($fechaInicio, '+1 year');

        return [
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_cierre' => $fechaCierre->format('Y-m-d'),
            'empresa_id' => $this->faker->randomElement($empresas),
            'ejercicio_fiscal' => (string) $this->faker->numberBetween(2020, 2024),
            'esta_cerrado' => $this->faker->numberBetween(0, 1), // tinyInteger (0 o 1)
        ];
    }
}
