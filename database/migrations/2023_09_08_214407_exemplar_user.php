<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $table->id();
        $table->foreignId('exemplar_id')->constrained();
        $table->foreignId('user_id')->constrained();
        $table->dateTime('borrowed');
        $table->dateTime('returned')->nullable()->default(null);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};