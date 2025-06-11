<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->foreignId('ticket_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        Schema::table('requisitions', function (Blueprint $table) {
            $table->foreignId('ticket_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->foreignId('job_order_id')->nullable()->after('ticket_id')->constrained('job_orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('job_order_id');
            $table->dropConstrainedForeignId('ticket_id');
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ticket_id');
        });
    }
};
