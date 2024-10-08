<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->foreignId('admin_id')->nullable()->constrained('admins');
            $table->foreignId('book_id')->nullable()->constrained('books');
            $table->enum('type', ['recommendations', 'due', 'fined']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
