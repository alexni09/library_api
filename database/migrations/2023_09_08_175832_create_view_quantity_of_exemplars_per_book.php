<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::unprepared("create view quantity_of_exemplars_per_book
        as select distinct book_id, count(*) as quantity 
        FROM exemplars 
        group by book_id
        union
        select distinct books.id as book_id, 0 as quantity 
        FROM books
        where not exists (
          select book_id 
          from exemplars
          where books.id = exemplars.book_id
        ) 
        group by book_id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::unprepared("drop view if exists quantity_of_exemplars_per_book;");
    }
};