<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('borrowing_id')->constrained('borrowings');
            $table->decimal('total_amount', 10, 2);
            $table->date('issue_date');
            $table->date('due_date');
            $table->enum('status', ['fined', 'clear']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
