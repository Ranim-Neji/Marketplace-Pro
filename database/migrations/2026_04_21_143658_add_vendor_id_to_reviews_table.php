<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->change(); // Allow reviews without products (pure vendor reviews)
            $table->dropUnique(['product_id', 'user_id']); // Drop old unique constraint
            $table->unique(['product_id', 'user_id', 'vendor_id'], 'reviews_unique_constraint');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_unique_constraint');
            $table->unique(['product_id', 'user_id']);
            $table->foreignId('product_id')->nullable(false)->change();
            $table->dropConstrainedForeignId('vendor_id');
        });
    }
};
