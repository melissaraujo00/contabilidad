<?php

use App\Models\FiscalPeriods;
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
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->string('book_number',45);
            $table->foreignIdFor(FiscalPeriods::class);
            $table->date('entry_date');
            $table->text('description');
            $table->string('document_number',45);
            $table->string('document_type',45);
            $table->string('status',45);
            $table->decimal('total_must', 15,4);
            $table->decimal('total_have',15,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diaries');
    }
};
