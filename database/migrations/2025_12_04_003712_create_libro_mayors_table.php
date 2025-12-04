<?php

use App\Models\CatalogoCuenta;
use App\Models\PeriodoFiscal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('libro_mayors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CatalogoCuenta::class)->constrained();
            $table->foreignIdFor(PeriodoFiscal::class)->constrained();
            $table->decimal('saldo_inicial', 15,4);
            $table->decimal('saldo_deudor', 15,4);
            $table->decimal('saldo_acreedor', 15,4);
            $table->decimal('saldo_final', 15,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_mayors');
    }
};
