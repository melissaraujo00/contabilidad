<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Illuminate\Http\Request;
use Inertia\Inertia;
 use Barryvdh\DomPDF\Facade\Pdf;

class LibroMayorController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', date('Y-m-01'));
        $fechaFin = $request->input('fecha_fin', date('Y-m-t'));
        $cuentaId = $request->input('cuenta_id');

        $query = CatalogoCuenta::query()
            ->with(['partidaDetalles' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereHas('partida', function ($p) use ($fechaInicio, $fechaFin) {
                    $p->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                        ->where('estado', true);
                })
                    ->with('partida');
            }])
            ->whereHas('partidaDetalles.partida', function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                    ->where('estado', true);
            });

        if ($cuentaId) {
            $query->where('id', $cuentaId);
        }

        $cuentas = $query->paginate(10);
        $cuentas->getCollection()->transform(function ($cuenta) {
            $detalles = $cuenta->partidaDetalles;

            $movimientosProcesados = $detalles->map(function ($d) {
                $debe = $d->monto_debe;
                $haber = $d->monto_haber;
                if ((float)$debe == 0 && (float)$haber == 0 && (float)$d->parcial > 0) {
                    if ($d->tipo_movimiento === 'DEBE') {
                        $debe = $d->parcial;
                    } elseif ($d->tipo_movimiento === 'HABER') {
                        $haber = $d->parcial;
                    }
                }

                return [
                    'fecha' => $d->partida->fecha_partida,
                    'partida_numero' => $d->partida->partida_numero,
                    'concepto' => $d->partida->description,
                    'debe' => $debe,
                    'haber' => $haber,
                ];
            });
            $totalDebe = $movimientosProcesados->sum('debe');
            $totalHaber = $movimientosProcesados->sum('haber');
            $saldo = $totalDebe - $totalHaber;

            return [
                'id' => $cuenta->id,
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->cuenta,
                'movimientos' => $movimientosProcesados,
                'total_debe' => $totalDebe,
                'total_haber' => $totalHaber,
                'saldo_final' => $saldo
            ];
        });

        $todasLasCuentas = CatalogoCuenta::select('id', 'codigo', 'cuenta')->orderBy('codigo')->get();

        return Inertia::render('libroMayor/Index', [
            'cuentas' => $cuentas,
            'todasCuentas' => $todasLasCuentas,
            'filtros' => compact('fechaInicio', 'fechaFin', 'cuentaId')
        ]);
    }


    public function reporte(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $cuentaId = $request->input('cuenta_id');

        $query = CatalogoCuenta::query()
            ->with(['partidaDetalles' => function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereHas('partida', function ($p) use ($fechaInicio, $fechaFin) {
                    $p->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                        ->where('estado', true);
                })
                    ->with('partida');
            }])
            ->whereHas('partidaDetalles.partida', function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha_partida', [$fechaInicio, $fechaFin])
                    ->where('estado', true);
            });

        if ($cuentaId) {
            $query->where('id', $cuentaId);
        }

        $cuentas = $query->get();

        $cuentasProcesadas = $cuentas->map(function ($cuenta) {
            $detalles = $cuenta->partidaDetalles;

            $movimientosProcesados = $detalles->map(function ($d) {
                $debe = $d->monto_debe;
                $haber = $d->monto_haber;

                if ((float)$debe == 0 && (float)$haber == 0 && (float)$d->parcial > 0) {
                    if ($d->tipo_movimiento === 'DEBE') {
                        $debe = $d->parcial;
                    } elseif ($d->tipo_movimiento === 'HABER') {
                        $haber = $d->parcial;
                    }
                }

                return [
                    'fecha' => $d->partida->fecha_partida,
                    'partida_numero' => $d->partida->partida_numero,
                    'concepto' => $d->partida->description,
                    'debe' => $debe,
                    'haber' => $haber,
                ];
            });

            return [
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->cuenta,
                'movimientos' => $movimientosProcesados,
                'total_debe' => $movimientosProcesados->sum('debe'),
                'total_haber' => $movimientosProcesados->sum('haber'),
            ];
        });

        // GENERAR PDF
        $pdf = Pdf::loadView('libromayor.reporte', [
            'cuentas' => $cuentasProcesadas,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin
        ]);

        return $pdf->stream('libro_mayor.pdf');
    }
}
