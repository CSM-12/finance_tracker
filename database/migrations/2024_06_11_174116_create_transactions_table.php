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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 1000)->nullable();
            $table->integer('amount')->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('type', ['in', 'out'])->default('out');
            $table->timestamps();
            // User
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
                
            // Foreign Keys
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
