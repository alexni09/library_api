<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('exemplar_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exemplar_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('borrowed');
            $table->timestamp('returned')->nullable()->default(null);
            $table->index(['returned', 'exemplar_id', 'borrowed']);
            $table->index(['user_id', 'exemplar_id', 'returned', 'borrowed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};