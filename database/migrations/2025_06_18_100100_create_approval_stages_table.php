<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_process_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('position');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_stages');
    }
};
