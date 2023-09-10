<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('exemplars', function (Blueprint $table) {
            $table->id();
            $table->boolean('borrowable')->default(true);
            $table->unsignedTinyInteger('condition')->default(1);
            $table->foreignId('book_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->setNullOnDelete()->cascadeOnUpdate()->default(null);
            $table->unsignedMediumInteger('maximum_minutes')->default(60);
            $table->unsignedMediumInteger('fee')->default(6000);               /* one shot */
            $table->unsignedMediumInteger('fine_per_delay')->default(2200);    /* one shot */
            $table->unsignedMediumInteger('fine_per_minute')->default(550);
            $table->unsignedMediumInteger('fine_per_loss')->default(260000);   /* one shot */
            $table->unsignedMediumInteger('fine_per_damage')->default(39000);  /* one shot */
            $table->timestamps();
            $table->unique(['borrowable', 'id']);
            $table->unique(['condition', 'borrowable', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exemplars');
    }
};