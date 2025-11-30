<?php

use App\Models\Empresa;
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
        Schema::create('periodo_fiscals', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio');
            $table->date('fecha_cierre');
            $table->foreignIdFor(Empresa::class)->nullable()->constrained();
            $table->string('ejercicio_fiscal', 45);
            $table->tinyInteger('esta_cerrado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodo_fiscals');
    }
};
