<?php

namespace App\Http\Controllers;

use App\Enums\TipoPartida;
use App\Http\Requests\StorePartidaRequest;
use App\Models\CatalogoCuenta;
use App\Models\Partida;
use App\Models\PeriodoFiscal;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    /**
     * @return Response
     */

    private function getPartidasFiltradas(?string $fecha_inicio = null, ?string $fecha_fin = null)
    {
        $fecha_inicio = $fecha_inicio  ?? date('Y-m-01');
        $fecha_fin= $fecha_fin ?? date('Y-m-t');

        return Partida::query()
            ->with('periodoFiscal')
            ->whereBetween('fecha_partida', [$fecha_inicio, $fecha_fin]);
    }

    public function index(): Response
    {
        $fecha_inicio = request()->input('fecha_inicio');
        $fecha_fin = request()->input('fecha_fin');

        $partidas = $this->getPartidasFiltradas($fecha_inicio, $fecha_fin)
            ->paginate(10)
            ->appends(request()->only('fecha_inicio', 'fecha_fin'));

        return Inertia::render('partidas/Index', [
            'partidas' => $partidas,
            'filtros' => compact('fecha_inicio', 'fecha_fin'),
        ]);
    }

    public function reporte(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $partidas = $this->getPartidasFiltradas($fechaInicio, $fechaFin)->get();

        $pdf = Pdf::loadView('partidas.reporte', compact('partidas', 'fechaInicio', 'fechaFin'));
        return $pdf->download('reporte_partidas.pdf');
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $periodos = PeriodoFiscal::query()
            ->select('id', 'fecha_inicio', 'fecha_cierre')
            ->where('esta_cerrado', 0)
            ->get();

        $tiposPartida = collect(TipoPartida::cases())->map(function ($case) {
            return [
                'value' => $case->name,
                'label' => $case->value,
            ];
        });

        $cuentas = CatalogoCuenta::query()
            ->where('esta_activo', true)
            ->orderBy('codigo')
            ->get(['id', 'codigo', 'cuenta', 'tipo_cuenta_id']);

        return Inertia::render('partidas/Create', compact('periodos', 'tiposPartida', 'cuentas'));
    }

    public function store(StorePartidaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $detalles = $data['detalles'];
            unset($data['detalles']);

            $data['total_debe'] = collect($detalles)->sum('monto_debe');
            $data['total_haber'] = collect($detalles)->sum('monto_haber');

            $partida = Partida::create($data);

            $detallesProcesados = collect($detalles)->map(function ($detalle) {
                return [
                    'catalogo_cuenta_id' => $detalle['catalogo_cuenta_id'],
                    'tipo_movimiento'    => $detalle['tipo_movimiento'] ?: 'DEBE',
                    'monto_debe'         => $detalle['monto_debe'] ?? 0,
                    'monto_haber'        => $detalle['monto_haber'] ?? 0,
                    'parcial'            => $detalle['parcial'] ?? 0,
                    'orden'              => $detalle['orden'],
                    'observaciones'      => $detalle['observaciones'] ?? null,
                ];
            });

            $partida->detalles()->createMany($detallesProcesados);
        });

        return to_route('partidas.index');
    }

    public function show(Partida $partida)
    {
        $partida->load('detalles');

        return Inertia::render('partidas/Show', compact('partida'));
    }
}
