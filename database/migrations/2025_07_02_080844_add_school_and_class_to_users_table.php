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
        Schema::table('users', function (Blueprint $table) {
            $table->string('school')->nullable()->after('password');
            $table->string('class')->nullable()->after('school');
            $table->string('class_letter', 1)->nullable()->after('class');
            $table->string('region')->nullable()->after('class_letter');
            $table->string('city')->nullable()->after('region');
            $table->string('district')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['school', 'class', 'class_letter', 'region', 'city', 'district']);
        });
    }
};
