<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryCategory;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreignId('inventory_category_id')
                ->nullable()
                ->after('description')
                ->constrained('inventory_categories');
        });

        DB::table('inventory_items')->select('id', 'category')->orderBy('id')->chunk(100, function ($items) {
            foreach ($items as $item) {
                if ($item->category) {
                    $category = InventoryCategory::firstOrCreate(
                        ['name' => $item->category],
                        ['is_active' => true]
                    );
                    DB::table('inventory_items')->where('id', $item->id)->update([
                        'inventory_category_id' => $category->id,
                    ]);
                }
            }
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->string('category')->nullable()->after('description');
        });

        DB::table('inventory_items')->select('id', 'inventory_category_id')->orderBy('id')->chunk(100, function ($items) {
            foreach ($items as $item) {
                if ($item->inventory_category_id) {
                    $name = DB::table('inventory_categories')->where('id', $item->inventory_category_id)->value('name');
                    DB::table('inventory_items')->where('id', $item->id)->update([
                        'category' => $name,
                    ]);
                }
            }
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('inventory_category_id');
        });
    }
};
