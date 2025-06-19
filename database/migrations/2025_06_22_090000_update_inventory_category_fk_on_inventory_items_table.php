<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_category_id']);
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreign('inventory_category_id')
                ->references('id')
                ->on('inventory_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_category_id']);
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreign('inventory_category_id')
                ->references('id')
                ->on('inventory_categories')
                ->cascadeOnDelete();
        });
    }
};
