<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->boolean('status')->default(true);
            $table->date('due_block')->nullable();
            $table->enum('role', ['guest', 'user', 'admin'])->default('user');
            $table->string('remember_token', 100)->nullable();
            $table->string('no_invoice')->nullable()->unique();
            $table->timestamps();
        });

    }

    public function down()
    {

        Schema::dropIfExists('users');
    }
};
