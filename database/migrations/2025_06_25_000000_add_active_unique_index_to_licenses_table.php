<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        $condition = $driver === 'sqlite' ? 'active = 1' : 'active = true';
        DB::statement("CREATE UNIQUE INDEX licenses_active_unique ON licenses(active) WHERE {$condition}");
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropIndex('licenses_active_unique');
        });
    }
};
