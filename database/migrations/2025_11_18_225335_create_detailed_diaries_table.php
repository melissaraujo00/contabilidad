<?php

use App\Models\ChartOfAccounts;
use App\Models\Diary;
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
        Schema::create('detailed_diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Diary::class)->constrained();
            $table->foreignIdFor(ChartOfAccounts::class)->constrained();
            $table->integer('lines');
            $table->decimal('must',15,4);
            $table->decimal('have', 15,4);
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailed_diaries');
    }
};
