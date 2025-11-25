<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCuenta;
use Inertia\Inertia;
use Inertia\Response;

class CatalogoCuentaController extends Controller
{
    public function index(): Response
    {
        $cuentas = CatalogoCuenta::query()
            ->with('cuentaPadre')
            ->select('id', 'codigo', 'cuenta', 'cuenta_padre_id', 'esta_activo')
            ->get();

        return Inertia::render('catalogoCuentas/Index', compact('cuentas'));
    }
}
