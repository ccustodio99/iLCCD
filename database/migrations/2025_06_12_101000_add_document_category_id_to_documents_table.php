<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('document_category_id')
                ->nullable()
                ->after('description')
                ->constrained('document_categories')
                ->cascadeOnSoftDelete();
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('category')->after('description');
            $table->dropConstrainedForeignId('document_category_id');
        });
    }
};
