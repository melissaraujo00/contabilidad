<?php

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
            $table->string('apellido', 50);
            $table->string('telefono',20);
            $table->string('email',50)->nullable();
            $table->string('nit',17)->nullable()->unique();
            $table->text('direccion')->nullable();
            $table->enum('tipo_cliente', ['consumidor', 'juridico', 'proveedor']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
