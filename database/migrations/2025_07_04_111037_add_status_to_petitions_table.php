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
            $table->enum('status', ['active', 'pending_review', 'approved', 'rejected'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petitions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
