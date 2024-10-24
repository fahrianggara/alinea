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
            $table->string('no_invoice')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('status', ['fined', 'clear'])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
