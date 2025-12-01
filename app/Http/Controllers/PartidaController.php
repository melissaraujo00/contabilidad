<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PartidaController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('partidas/Index');
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('partidas/Create');
    }
}
