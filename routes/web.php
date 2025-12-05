<?php

use App\Http\Controllers\BalanceComprobacionController;
use App\Http\Controllers\BalanceGeneralController;
use App\Http\Controllers\CatalogoCuentaController;
use App\Http\Controllers\EmpresaControlador;
use App\Http\Controllers\EstadoResultadoController;
use App\Http\Controllers\LibroMayorController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\PeriodoFiscalController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Middleware\HandleInertiaRequests;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/libro-mayor/reporte', [LibroMayorController::class, 'reporte'])
    ->name('libromayor.reporte');

Route::resource('catalogo-cuentas', CatalogoCuentaController::class)->only('index');

Route::resource('empresa', EmpresaControlador::class);

Route::resource('periodo-fiscal', PeriodoFiscalController::class);

Route::resource('partidas', PartidaController::class);
Route::resource('libro-mayor', LibroMayorController::class);
Route::resource('balance-general',BalanceGeneralController::class);
Route::resource('balance-comprobacion', BalanceComprobacionController::class);
Route::resource('estado-resultado', EstadoResultadoController::class);

require __DIR__ . '/settings.php';
