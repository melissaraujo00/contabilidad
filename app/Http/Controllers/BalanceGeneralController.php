<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BalanceGeneralController extends Controller
{
    public function index(Request $request)
    {
        $fechaCorte = $request->input('fecha_corte', date('Y-m-d'));

        // 1. Traemos TODAS las cuentas con movimientos hasta la fecha de corte
        $cuentas = CatalogoCuenta::with(['partidaDetalles' => function ($q) use ($fechaCorte) {
            $q->whereHas('partida', function ($p) use ($fechaCorte) {
                $p->where('fecha_partida', '<=', $fechaCorte)
                  ->where('estado', true);
            });
        }])->get();

        // 2. Procesamos los saldos en memoria
        $cuentasProcesadas = $cuentas->map(function ($cuenta) {
            $saldo = 0;

            foreach ($cuenta->partidaDetalles as $d) {
                $debe = $d->monto_debe;
                $haber = $d->monto_haber;

                // APLICAMOS LA LÓGICA DE PARCIAL (Igual que en Libro Mayor)
                if ((float)$debe == 0 && (float)$haber == 0 && (float)$d->parcial > 0) {
                    if ($d->tipo_movimiento === 'DEBE') $debe = $d->parcial;
                    if ($d->tipo_movimiento === 'HABER') $haber = $d->parcial;
                }

                // Saldo según naturaleza aproximada por código
                // 1 y 5 (y 6) son Deudoras (+Debe -Haber)
                // 2, 3 y 4 son Acreedoras (+Haber -Debe)
                $prefix = substr($cuenta->codigo, 0, 1);

                if (in_array($prefix, ['1', '5', '6'])) {
                    $saldo += ($debe - $haber);
                } else {
                    $saldo += ($haber - $debe);
                }
            }

            $cuenta->saldo_actual = $saldo;
            return $cuenta;
        })->filter(function ($c) {
            // Solo nos quedamos con cuentas que tengan saldo o sean rubros principales
            return abs($c->saldo_actual) > 0;
        });

        // 3. Clasificación por Rubros (Asumiendo codificación estándar)
        $activos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '1'))->values();
        $pasivos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '2'))->values();
        $patrimonio = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '3'))->values();

        // 4. Cálculo del Resultado del Ejercicio (Ingresos - Gastos)
        // Cuentas 4 (Ingresos - Acreedoras) vs Cuentas 5 (Gastos - Deudoras)
        $ingresos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '4'))->sum('saldo_actual');
        $gastos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '5') || str_starts_with($c->codigo, '6'))->sum('saldo_actual');

        $resultadoEjercicio = $ingresos - $gastos;

        // 5. Totales
        $totalActivo = $activos->sum('saldo_actual');
        $totalPasivo = $pasivos->sum('saldo_actual');
        $totalPatrimonio = $patrimonio->sum('saldo_actual') + $resultadoEjercicio;

        return Inertia::render('balanceGeneral/Index', [
            'fechaCorte' => $fechaCorte,
            'activos' => $activos,
            'pasivos' => $pasivos,
            'patrimonio' => $patrimonio,
            'resultadoEjercicio' => $resultadoEjercicio,
            'totales' => compact('totalActivo', 'totalPasivo', 'totalPatrimonio')
        ]);
    }
}
