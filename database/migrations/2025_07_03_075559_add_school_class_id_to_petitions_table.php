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
        Schema::table('petitions', function (Blueprint $table) {
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->onDelete('set null');
            $table->dropColumn('target_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petitions', function (Blueprint $table) {
            $table->string('target_class')->nullable();
            $table->dropForeign(['school_class_id']);
            $table->dropColumn('school_class_id');
        });
    }
};
