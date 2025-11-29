<?php

use App\Http\Controllers\CatalogoCuentaController;
use App\Http\Controllers\EmpresaControlador;
use App\Models\Empresa;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('catalogo-cuentas', CatalogoCuentaController::class)->only('index');

Route::resource('empresa', EmpresaControlador::class);


require __DIR__ . '/settings.php';
