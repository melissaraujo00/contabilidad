<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LibroMayorController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', date('Y-m-01'));
        $fechaFin = $request->input('fecha_fin', date('Y-m-t'));
        $cuentaId = $request->input('cuenta_id');

        // 1. CAMBIO AQUÍ: Usamos 'partidaDetalles' en lugar de 'detalles'
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
            // 2. CAMBIO AQUÍ: Usamos $cuenta->partidaDetalles
            $detalles = $cuenta->partidaDetalles;

            $totalDebe = $detalles->where('tipo_movimiento', 'DEBE')->sum('monto_debe');
            $totalHaber = $detalles->where('tipo_movimiento', 'HABER')->sum('monto_haber');

            // Lógica básica: Saldo Deudor (Debe - Haber)
            // Si tuvieras lógica de cuentas acreedoras, aquí harías el if/else
            $saldo = $totalDebe - $totalHaber;

            return [
                'id' => $cuenta->id,
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->cuenta,
                // 3. CAMBIO AQUÍ: Mapeamos partidaDetalles
                'movimientos' => $detalles->map(function ($d) {
                    return [
                        'fecha' => $d->partida->fecha_partida,
                        'partida_numero' => $d->partida->partida_numero,
                        'concepto' => $d->partida->description,
                        'debe' => $d->monto_debe,
                        'haber' => $d->monto_haber,
                    ];
                }),
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
}
