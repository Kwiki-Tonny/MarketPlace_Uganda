<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'vendor'])->default('vendor');
            $table->boolean('is_approved')->default(false);
            $table->string('id_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('district')->nullable();
            $table->string('category_interest')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_approved', 'id_number', 'phone', 'district', 'category_interest']);
        });
    }
};