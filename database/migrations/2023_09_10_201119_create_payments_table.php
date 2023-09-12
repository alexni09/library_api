<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exemplar_id')->constrained()->cascadeOnUpdate()->setNullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->setNullOnDelete();
            $table->unsignedMediumInteger('due_value');
            $table->timestamp('due_from');
            $table->timestamp('due_at');
            $table->timestamp('paid_at')->nullable()->default(null);
            $table->unique(['paid_at','user_id','due_at','id']);
            $table->unique(['paid_at','exemplar_id','due_at','id']);
            $table->index(['paid_at','user_id','due_value']);
            $table->index(['user_id','due_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};