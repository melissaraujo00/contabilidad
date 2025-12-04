<?php

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
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->string('partida_numero');
            $table->foreignIdFor(PeriodoFiscal::class)->constrained();
            $table->date('fecha_partida');
            $table->string('tipo_partida');
            $table->text('description')->nullable();
            $table->boolean('estado')->default(1);
            $table->decimal('total_debe', 15, 2)->default(0);
            $table->decimal('total_haber', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
