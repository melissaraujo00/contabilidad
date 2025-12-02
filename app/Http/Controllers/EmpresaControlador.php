<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Enums\TipoEmpresa;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use Inertia\Inertia;
use Inertia\Response;

class EmpresaControlador extends Controller
{
    public function index(): Response
    {
        $empresas = Empresa::select('id', 'nombre', 'tipo_empresa', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('empresa/Index', [
            'empresas' => $empresas,
            'tiposEmpresa' => array_column(TipoEmpresa::cases(), 'value')
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('empresa/Create', [
            'tiposEmpresa' => array_column(TipoEmpresa::cases(), 'value')
        ]);
    }

    public function store(StoreEmpresaRequest $request)
    {
        try {
            Empresa::create($request->validated());

            return redirect()
                ->route('empresa.index')
                ->with('success', 'Empresa creada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear la empresa');
        }
    }

    public function show(Empresa $empresa): Response
    {
        return Inertia::render('empresa/Show', [
            'empresa' => $empresa
        ]);
    }

    public function edit(Empresa $empresa): Response
    {
        return Inertia::render('empresa/Edit', [
            'empresa' => $empresa,
            'tiposEmpresa' => array_column(TipoEmpresa::cases(), 'value')
        ]);
    }

    public function update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        try {
            $empresa->update($request->validated());

            return redirect()
                ->route('empresa.index')
                ->with('success', 'Empresa actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la empresa');
        }
    }

    public function destroy(Empresa $empresa)
    {
        try {
            $empresa->delete();

            return redirect()
                ->route('empresa.index')
                ->with('success', 'Empresa eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la empresa');
        }
    }
}
