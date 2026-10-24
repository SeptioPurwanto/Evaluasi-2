<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id')->nullable(); // null = pembeli tamu
            $table->string('name', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->text('note')->nullable();
            $table->decimal('total', 12, 2);
            $table->string('status', 20)->default('baru'); // baru | diproses | selesai
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
