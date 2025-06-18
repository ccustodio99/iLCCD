<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_stages', function (Blueprint $table) {
            if (! Schema::hasColumn('approval_stages', 'assigned_user_id')) {
                $table->foreignId('assigned_user_id')
                    ->nullable()
                    ->after('position')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('approval_stages', function (Blueprint $table) {
            if (Schema::hasColumn('approval_stages', 'assigned_user_id')) {
                $table->dropForeign(['assigned_user_id']);
                $table->dropColumn('assigned_user_id');
            }
        });
    }
};
