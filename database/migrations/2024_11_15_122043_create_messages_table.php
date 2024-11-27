<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('room_id')->constrained('rooms', 'id');
            $table->text('text')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('messages');
    }
};
