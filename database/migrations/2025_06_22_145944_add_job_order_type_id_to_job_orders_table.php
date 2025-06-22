<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->foreignId('job_order_type_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->nullOnDelete();
        });

        $orders = DB::table('job_orders')->select('id', 'job_type')->get();

        foreach ($orders as $order) {
            $typeId = DB::table('job_order_types')
                ->where('name', $order->job_type)
                ->value('id');

            if ($typeId) {
                DB::table('job_orders')
                    ->where('id', $order->id)
                    ->update(['job_order_type_id' => $typeId]);
            }
        }

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn('job_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->string('job_type')->nullable()->after('user_id');
        });

        $orders = DB::table('job_orders')->select('id', 'job_order_type_id')->get();

        foreach ($orders as $order) {
            $name = DB::table('job_order_types')
                ->where('id', $order->job_order_type_id)
                ->value('name');

            if ($name) {
                DB::table('job_orders')
                    ->where('id', $order->id)
                    ->update(['job_type' => $name]);
            }
        }

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropForeign(['job_order_type_id']);
            $table->dropColumn('job_order_type_id');
        });
    }
};
