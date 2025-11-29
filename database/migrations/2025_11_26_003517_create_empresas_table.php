<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TipoEmpresa;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->enum('tipo_empresa', [
                TipoEmpresa::EMPRESA->value,
                TipoEmpresa::SOCIEDAD->value,
                TipoEmpresa::SOCIEDAD_ANONIMA->value,
                TipoEmpresa::SOCIEDAD_VARIABLE->value,
                TipoEmpresa::SOCIEDAD_COLECTIVO->value,
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
