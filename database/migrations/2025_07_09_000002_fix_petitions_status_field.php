<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // If the petitions table doesn't exist, create it first
        if (!Schema::hasTable('petitions')) {
            Schema::create('petitions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('title', 100);
                $table->text('description');
                $table->integer('signatures_required');
                $table->integer('duration');
                $table->foreignId('school_class_id')->nullable()->constrained()->nullOnDelete();
                $table->enum('status', ['active', 'pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
                $table->softDeletes();
            });
        } 
        // If the table exists but doesn't have the status column
        else if (!Schema::hasColumn('petitions', 'status')) {
            Schema::table('petitions', function (Blueprint $table) {
                $table->enum('status', ['active', 'pending', 'approved', 'rejected'])->default('pending');
            });
        }
        // If the table and column exist, try to modify the enum
        else {
            // For MySQL: Alter the enum values
            try {
                DB::statement("ALTER TABLE petitions MODIFY COLUMN status ENUM('active', 'pending', 'approved', 'rejected') DEFAULT 'pending'");
            } catch (\Exception $e) {
                // For other databases, do nothing as the column already exists
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop the table or column on rollback
        // Just revert the enum values if possible
        if (Schema::hasTable('petitions') && Schema::hasColumn('petitions', 'status')) {
            try {
                DB::statement("ALTER TABLE petitions MODIFY COLUMN status ENUM('active', 'approved', 'rejected') DEFAULT 'active'");
            } catch (\Exception $e) {
                // For other databases, do nothing
            }
        }
    }
}; 