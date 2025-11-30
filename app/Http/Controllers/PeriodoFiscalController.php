<?php

namespace App\Http\Controllers;

use App\Models\PeriodoFiscal;
use App\Models\Empresa;
use App\Http\Requests\StorePeriodoFiscalRequest;
use App\Http\Requests\UpdatePeriodoFiscalRequest;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PeriodoFiscalController extends Controller
{
    public function index()
    {
        $periodosFiscales = PeriodoFiscal::with('empresa')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return Inertia::render('periodoFiscal/Index', [
            'periodosFiscales' => $periodosFiscales,
            'empresas' => Empresa::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('periodoFiscal/Create', [
            'empresas' => Empresa::all()
        ]);
    }

    public function store(StorePeriodoFiscalRequest $request)
    {
        PeriodoFiscal::create($request->validated());

        return redirect()->route('periodo-fiscal.index')
            ->with('success', 'Período fiscal creado exitosamente.');
    }

    public function edit(PeriodoFiscal $periodoFiscal)
    {
        $periodoFiscal->load('empresa');

        return Inertia::render('periodoFiscal/Edit', [
            'periodoFiscal' => $periodoFiscal,
            'empresas' => Empresa::all()
        ]);
    }

    public function update(UpdatePeriodoFiscalRequest $request, PeriodoFiscal $periodoFiscal)
    {
        $periodoFiscal->update($request->validated());

        return redirect()->route('periodo-fiscal.index')
            ->with('success', 'Período fiscal actualizado exitosamente.');
    }

    public function destroy(PeriodoFiscal $periodoFiscal)
    {
        // Verificar si existe la relación asientosContables antes de usarla
        if (method_exists($periodoFiscal, 'asientosContables') && $periodoFiscal->asientosContables()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el período fiscal porque tiene asientos contables asociados.');
        }

        $periodoFiscal->delete();

        return redirect()->route('periodo-fiscal.index')
            ->with('success', 'Período fiscal eliminado exitosamente.');
    }
}
