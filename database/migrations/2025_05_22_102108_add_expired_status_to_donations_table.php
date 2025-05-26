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
        // Modify the status column to include 'expired'
        DB::statement("ALTER TABLE donations MODIFY COLUMN status ENUM('available', 'reserved', 'completed', 'expired') DEFAULT 'available'");
        
        // Update existing expired donations
        DB::statement("UPDATE donations SET status = 'expired' WHERE best_before < CURDATE() AND status = 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert expired donations back to available
        DB::statement("UPDATE donations SET status = 'available' WHERE status = 'expired'");
        
        // Remove the expired status from enum
        DB::statement("ALTER TABLE donations MODIFY COLUMN status ENUM('available', 'reserved', 'completed') DEFAULT 'available'");
    }
};