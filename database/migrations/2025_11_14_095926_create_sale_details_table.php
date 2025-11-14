<?php

use App\Models\Product;
use App\Models\Sale;
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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('precio',12,2);
            $table->decimal('subtotal',12,2);
            $table->decimal('iva',12,2);
            $table->decimal('monto', 12,2);
            $table->foreignIdFor(Sale::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
