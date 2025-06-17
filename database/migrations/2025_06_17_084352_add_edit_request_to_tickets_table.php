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
        Schema::table('tickets', function (Blueprint $table) {
            $table->text('edit_request_reason')->nullable()->after('resolved_at');
            $table->timestamp('edit_requested_at')->nullable()->after('edit_request_reason');
            $table->foreignId('edit_requested_by')->nullable()->constrained('users')->after('edit_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('edit_requested_by');
            $table->dropColumn(['edit_request_reason', 'edit_requested_at']);
        });
    }
};
