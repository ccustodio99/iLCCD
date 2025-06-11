<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->string('item')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->string('item')->nullable(false)->change();
            $table->integer('quantity')->nullable(false)->change();
        });
    }
};
