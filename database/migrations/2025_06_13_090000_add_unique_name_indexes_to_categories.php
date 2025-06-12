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
        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('job_order_types', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('inventory_categories', function (Blueprint $table) {
            $table->unique('name');
        });
        Schema::table('document_categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('job_order_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('inventory_categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
