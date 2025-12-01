<?php

use App\Models\CatalogoCuenta;
use App\Models\Partida;
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
        Schema::create('partida_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Partida::class)->constrained();
            $table->foreignIdFor(CatalogoCuenta::class)->constrained();
            $table->text('description')->nullable();
            $table->string('tipo_movimiento');
            $table->decimal('monto', 14, 4);
            $table->decimal('parcial', 14, 4)->default(0);
            $table->integer('orden');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partida_detalles');
    }
};
