<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BalanceComprobacionController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', date('Y-01-01'));
        $fechaFin = $request->input('fecha_fin', date('Y-m-d'));

        $cuentas = CatalogoCuenta::with(['partidaDetalles' => function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereHas('partida', function ($p) use ($fechaInicio, $fechaFin) {
                $p->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                  ->where('estado', true);
            });
        }])->orderBy('codigo')->get();

        $cuentasProcesadas = $cuentas->map(function ($cuenta) {
            $sumaDebe = 0;
            $sumaHaber = 0;

            foreach ($cuenta->partidaDetalles as $d) {
                $debe = $d->monto_debe;
                $haber = $d->monto_haber;

                if ((float)$debe == 0 && (float)$haber == 0 && (float)$d->parcial > 0) {
                    if ($d->tipo_movimiento === 'DEBE') $debe = $d->parcial;
                    if ($d->tipo_movimiento === 'HABER') $haber = $d->parcial;
                }

                $sumaDebe += $debe;
                $sumaHaber += $haber;
            }

            $saldo = $sumaDebe - $sumaHaber;

            return [
                'id' => $cuenta->id,
                'codigo' => $cuenta->codigo,
                'cuenta' => $cuenta->cuenta,
                'suma_debe' => $sumaDebe,
                'suma_haber' => $sumaHaber,
                'saldo_deudor' => $saldo > 0 ? $saldo : 0,
                'saldo_acreedor' => $saldo < 0 ? abs($saldo) : 0,
            ];
        })->filter(function ($c) {
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
