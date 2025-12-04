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

class PartidaController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $partidas = Partida::query()
            ->with('periodoFiscal')
            ->paginate(10);

        return Inertia::render('partidas/Index', compact('partidas'));
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

        $partida->detalles()->createMany($detalles);
        });

        return to_route('partidas.index');
    }
}
