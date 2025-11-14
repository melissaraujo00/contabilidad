<?php

use App\Models\Customer;
use App\Models\DocumentType;
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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('numero_documento', 45);
            $table->date('fecha');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('iva', 12, 2)->nullable();
            $table->decimal('total', 12,2);
            $table->foreignIdFor(Customer::class)->constrained();
            $table->foreignIdFor(DocumentType::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
