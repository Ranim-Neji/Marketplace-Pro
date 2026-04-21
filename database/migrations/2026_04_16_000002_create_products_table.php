<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // vendor/seller
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('image'); // main image
            $table->integer('stock')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes(); // for safe deletion
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
