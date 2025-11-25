<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class CatalogoCuentaController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('catalogoCuentas/Index');
    }
}
