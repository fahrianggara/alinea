<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowingsTable extends Migration
{
    public function up()
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('book_id')->constrained('books');
            $table->date('borrow_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['borrowed', 'returned']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowings');
    }
}
