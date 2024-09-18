<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->string('title', 1000)->nullable();
            $table->longText('description')->nullable();
            $table->integer('amount')->default(0);
            $table->date('due_date')->default(DB::raw('DATE_ADD(CURDATE(), INTERVAL 1 DAY)'));
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('savings');
    }
};
