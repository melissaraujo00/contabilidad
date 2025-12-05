<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BalanceComprobacionController extends Controller
{
    public function index(Request $request)
    {
        // Por defecto tomamos desde el inicio del año hasta hoy, o lo que el usuario filtre
        $fechaInicio = $request->input('fecha_inicio', date('Y-01-01'));
        $fechaFin = $request->input('fecha_fin', date('Y-m-d'));

        // 1. Consulta: Traer cuentas con movimientos en el rango
        $cuentas = CatalogoCuenta::with(['partidaDetalles' => function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereHas('partida', function ($p) use ($fechaInicio, $fechaFin) {
                $p->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                  ->where('estado', true);
            });
        }])->orderBy('codigo')->get();

        // 2. Procesamiento de Sumas y Saldos
        $cuentasProcesadas = $cuentas->map(function ($cuenta) {
            $sumaDebe = 0;
            $sumaHaber = 0;

            foreach ($cuenta->partidaDetalles as $d) {
                $debe = $d->monto_debe;
                $haber = $d->monto_haber;

                // LÓGICA DE PARCIAL (Reutilizada)
                // Si Debe y Haber son 0, pero hay Parcial, asignamos según el tipo
                if ((float)$debe == 0 && (float)$haber == 0 && (float)$d->parcial > 0) {
                    if ($d->tipo_movimiento === 'DEBE') $debe = $d->parcial;
                    if ($d->tipo_movimiento === 'HABER') $haber = $d->parcial;
                }

                $sumaDebe += $debe;
                $sumaHaber += $haber;
            }

            // Determinamos saldo
            $saldo = $sumaDebe - $sumaHaber;

            return [
                'id' => $cuenta->id,
                'codigo' => $cuenta->codigo,
                'cuenta' => $cuenta->cuenta,
                'suma_debe' => $sumaDebe,
                'suma_haber' => $sumaHaber,
                // Si es positivo es Deudor, si es negativo es Acreedor
                'saldo_deudor' => $saldo > 0 ? $saldo : 0,
                'saldo_acreedor' => $saldo < 0 ? abs($saldo) : 0,
            ];
        })->filter(function ($c) {
            // Filtramos cuentas que no tengan ningún movimiento ni saldo
            return ($c['suma_debe'] + $c['suma_haber'] + $c['saldo_deudor'] + $c['saldo_acreedor']) > 0;
        })->values();

        // 3. Totales Generales para verificar cuadre
        $totales = [
            'debe' => $cuentasProcesadas->sum('suma_debe'),
            'haber' => $cuentasProcesadas->sum('suma_haber'),
            'deudor' => $cuentasProcesadas->sum('saldo_deudor'),
            'acreedor' => $cuentasProcesadas->sum('saldo_acreedor'),
        ];

        return Inertia::render('balanceComprobacion/Index', [
            'filtros' => compact('fechaInicio', 'fechaFin'),
            'cuentas' => $cuentasProcesadas,
            'totales' => $totales
        ]);
    }
}
