<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstadoResultadoController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', date('Y-01-01'));
        $fechaFin = $request->input('fecha_fin', date('Y-m-d'));

        // 1. Traer cuentas de RESULTADOS (4=Ingresos, 5=Gastos, 6=Costos)
        $cuentas = CatalogoCuenta::where(function($q) {
                $q->where('codigo', 'like', '4%')
                  ->orWhere('codigo', 'like', '5%')
                  ->orWhere('codigo', 'like', '6%');
            })
            ->with(['partidaDetalles' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereHas('partida', function ($p) use ($fechaInicio, $fechaFin) {
                    $p->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                      ->where('estado', true);
                });
            }])
            ->orderBy('codigo')
            ->get();

        // 2. Calcular Saldos del Periodo
        $cuentasProcesadas = $cuentas->map(function ($cuenta) {
            $saldo = 0;

            foreach ($cuenta->partidaDetalles as $d) {
                $debe = $d->monto_debe;
                $haber = $d->monto_haber;

                // LÓGICA DE PARCIAL (Reutilizada)
                if ((float)$debe == 0 && (float)$haber == 0 && (float)$d->parcial > 0) {
                    if ($d->tipo_movimiento === 'DEBE') $debe = $d->parcial;
                    if ($d->tipo_movimiento === 'HABER') $haber = $d->parcial;
                }

                // Cálculo según naturaleza
                // 4 (Ingresos) = Acreedora (+Haber -Debe)
                // 5 y 6 (Gastos/Costos) = Deudora (+Debe -Haber)
                if (str_starts_with($cuenta->codigo, '4')) {
                    $saldo += ($haber - $debe);
                } else {
                    $saldo += ($debe - $haber);
                }
            }

            $cuenta->saldo_periodo = $saldo;
            return $cuenta;
        })->filter(function ($c) {
            // Solo mostramos cuentas con movimiento
            return abs($c->saldo_periodo) > 0;
        });

        // 3. Agrupación por Rubros
        $ingresos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '4'))->values();
        $costos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '6'))->values(); // Si usas clase 6 para Costos
        $gastos = $cuentasProcesadas->filter(fn($c) => str_starts_with($c->codigo, '5'))->values();

        // 4. Totales
        $totalIngresos = $ingresos->sum('saldo_periodo');
        $totalCostos = $costos->sum('saldo_periodo');

        $utilidadBruta = $totalIngresos - $totalCostos;

        $totalGastos = $gastos->sum('saldo_periodo');

        $utilidadNeta = $utilidadBruta - $totalGastos;

        return Inertia::render('estadoResultado/Index', [
            'filtros' => compact('fechaInicio', 'fechaFin'),
            'ingresos' => $ingresos,
            'costos' => $costos,
            'gastos' => $gastos,
            'totales' => compact('totalIngresos', 'totalCostos', 'totalGastos', 'utilidadBruta', 'utilidadNeta')
        ]);
    }
}
