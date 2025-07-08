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
        // SQLite uses different syntax for modifying CHECK constraints
        // We need to drop and recreate the check constraint
        if (DB::connection()->getDriverName() === 'sqlite') {
            // For SQLite, we need to recreate the table
            // This is simplified for testing purposes
            DB::statement("CREATE TEMPORARY TABLE petitions_backup AS SELECT * FROM petitions");
            DB::statement("DROP TABLE petitions");
            
            // Get the original create table statement and modify it
            $createTable = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='petitions_backup'");
            if (!empty($createTable)) {
                $originalSql = $createTable[0]->sql;
                // Replace the CHECK constraint
                $newSql = preg_replace("/CHECK\s*\(\s*status\s+IN\s+\([^)]+\)\s*\)/i", 
                           "CHECK (status IN ('active', 'approved', 'rejected', 'pending'))", 
                           $originalSql);
                DB::statement($newSql);
                
                // Copy data back
                DB::statement("INSERT INTO petitions SELECT * FROM petitions_backup");
                DB::statement("DROP TABLE petitions_backup");
            }
        } else {
            // For other databases like MySQL, PostgreSQL
            Schema::table('petitions', function (Blueprint $table) {
                // Drop the existing check constraint (name may vary)
                $table->dropIndex('petitions_status_check');
                
                // Add the modified check constraint
                DB::statement("ALTER TABLE petitions ADD CONSTRAINT petitions_status_check CHECK (status IN ('active', 'approved', 'rejected', 'pending'))");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original constraint
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Similar approach as in up() but reverting the changes
            DB::statement("CREATE TEMPORARY TABLE petitions_backup AS SELECT * FROM petitions");
            DB::statement("DROP TABLE petitions");
            
            $createTable = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='petitions_backup'");
            if (!empty($createTable)) {
                $originalSql = $createTable[0]->sql;
                $newSql = preg_replace("/CHECK\s*\(\s*status\s+IN\s+\([^)]+\)\s*\)/i", 
                           "CHECK (status IN ('active', 'approved', 'rejected'))", 
                           $originalSql);
                DB::statement($newSql);
                
                DB::statement("INSERT INTO petitions SELECT * FROM petitions_backup WHERE status != 'pending'");
                DB::statement("DROP TABLE petitions_backup");
            }
        } else {
            Schema::table('petitions', function (Blueprint $table) {
                $table->dropIndex('petitions_status_check');
                
                DB::statement("ALTER TABLE petitions ADD CONSTRAINT petitions_status_check CHECK (status IN ('active', 'approved', 'rejected'))");
            });
        }
    }
};
