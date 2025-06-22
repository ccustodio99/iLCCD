<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('role')->constrained()->nullOnDelete();
        });
        Schema::table('requisitions', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('inventory_category_id')->constrained()->nullOnDelete();
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('document_category_id')->constrained()->nullOnDelete();
        });
        Schema::table('approval_processes', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('module')->constrained()->nullOnDelete();
        });

        // backfill
        $names = collect();
        foreach (['users', 'requisitions', 'inventory_items', 'documents', 'approval_processes'] as $table) {
            $names = $names->merge(DB::table($table)->pluck('department')->filter());
        }
        $names = $names->unique();
        $map = [];
        foreach ($names as $name) {
            $map[$name] = DB::table('departments')->insertGetId([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($map as $name => $id) {
            DB::table('users')->where('department', $name)->update(['department_id' => $id]);
            DB::table('requisitions')->where('department', $name)->update(['department_id' => $id]);
            DB::table('inventory_items')->where('department', $name)->update(['department_id' => $id]);
            DB::table('documents')->where('department', $name)->update(['department_id' => $id]);
            DB::table('approval_processes')->where('department', $name)->update(['department_id' => $id]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department');
        });
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn('department');
        });
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('department');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('department');
        });
        Schema::table('approval_processes', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable();
        });
        Schema::table('requisitions', function (Blueprint $table) {
            $table->string('department')->nullable();
        });
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->string('department')->nullable();
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->string('department')->nullable();
        });
        Schema::table('approval_processes', function (Blueprint $table) {
            $table->string('department')->nullable();
        });

        $departments = DB::table('departments')->get()->keyBy('id');
        foreach (['users', 'requisitions', 'inventory_items', 'documents', 'approval_processes'] as $table) {
            $rows = DB::table($table)->select('id', 'department_id')->get();
            foreach ($rows as $row) {
                $name = $departments[$row->department_id]->name ?? null;
                DB::table($table)->where('id', $row->id)->update(['department' => $name]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });
        Schema::table('approval_processes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
        });

        Schema::dropIfExists('departments');
    }
};
