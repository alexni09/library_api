<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::unprepared("drop view if exists quantity_of_exemplars_per_book;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};