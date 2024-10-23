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
            $table->string('no_invoice')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('book_id')->constrained('books');
            $table->date('borrow_date')->nullable();
            $table->date('return_date')->nullable();
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowings');
    }
}
