<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreignId('requisition_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('job_order_id')->nullable()->after('requisition_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('job_order_id');
            $table->dropConstrainedForeignId('requisition_id');
        });
    }
};
