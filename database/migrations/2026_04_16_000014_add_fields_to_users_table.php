<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('phone')->nullable()->after('bio');
            $table->string('address')->nullable()->after('phone');
            $table->boolean('is_vendor')->default(false)->after('address');
            $table->string('shop_name')->nullable()->after('is_vendor');
            $table->text('shop_description')->nullable()->after('shop_name');
            $table->boolean('is_active')->default(true)->after('shop_description');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'bio', 'phone', 'address',
                'is_vendor', 'shop_name', 'shop_description', 'is_active'
            ]);
        });
    }
};
