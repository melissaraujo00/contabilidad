<?php

use App\Models\CatalogoCuenta;
use App\Models\TipoCuenta;
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
        Schema::create('catalogo_cuentas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20);
            $table->string('cuenta', 100);
            $table->foreignIdFor(TipoCuenta::class)->nullable()->constrained();

            $table->unsignedBigInteger('cuenta_padre_id')->nullable();
            $table->foreign('cuenta_padre_id')
                ->references('id')
                ->on('catalogo_cuentas')
                ->onDelete('cascade');

            $table->boolean('esta_activo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogo_cuentas');
    }
};
