<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            if (Schema::hasColumn('audit_trails', 'comment')) {
                $table->dropColumn('comment');
            }
        });
    }

    public function down(): void
    {
        Schema::table('audit_trails', function (Blueprint $table) {
            if (! Schema::hasColumn('audit_trails', 'comment')) {
                $table->text('comment')->nullable();
            }
        });
    }
};
