<<<<<<< HEAD
=======

>>>>>>> ajax-smart-search
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
<<<<<<< HEAD
            // Drop foreign keys first to unlock the unique index
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
            
            $table->dropUnique(['product_id', 'user_id']); // Now we can drop it safely
            
            $table->foreignId('vendor_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->change(); 
            
            // Re-add foreign keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['product_id', 'user_id', 'vendor_id'], 'reviews_unique_constraint');
=======

            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);

           
            $table->dropUnique('reviews_product_id_user_id_unique');

           
            $table->foreignId('vendor_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

         
            $table->foreignId('product_id')->nullable()->change();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unique(
                ['product_id', 'user_id', 'vendor_id'],
                'reviews_unique_constraint'
            );
>>>>>>> ajax-smart-search
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
<<<<<<< HEAD
            $table->dropUnique('reviews_unique_constraint');
            $table->dropForeign(['vendor_id']);
            $table->dropColumn('vendor_id');
            
            $table->unique(['product_id', 'user_id']);
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
=======

            $table->dropUnique('reviews_unique_constraint');

           
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['vendor_id']);

            $table->unique(['product_id', 'user_id']);

           
            $table->foreignId('product_id')->nullable(false)->change();

            
            $table->dropColumn('vendor_id');

            
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
>>>>>>> ajax-smart-search
