<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Donation;
use Carbon\Carbon;

// Cleanup expired donations command
Artisan::command('donations:cleanup-expired {--dry-run : Show what would be updated without actually updating}', function () {
    $this->info('Starting expired donations cleanup...');

    // Find donations that are expired and still marked as available
    // Now we use subDay() to ensure it's strictly after the best before date
    $expiredDonations = Donation::where('status', 'available')
        ->where('best_before', '<', Carbon::today()->subDay())
        ->get();

    $this->info("Found {$expiredDonations->count()} expired donations to update.");

    if ($this->option('dry-run')) {
        $this->warn('DRY RUN MODE - No changes will be made');
        
        if ($expiredDonations->count() > 0) {
            $this->table(
                ['ID', 'Food Description', 'Best Before', 'Days Expired'],
                $expiredDonations->map(function ($donation) {
                    return [
                        $donation->id,
                        \Str::limit($donation->food_description, 30),
                        $donation->best_before->format('Y-m-d'),
                        Carbon::today()->diffInDays($donation->best_before) . ' days'
                    ];
                })->toArray()
            );
        }
        
        return;
    }

    // Update expired donations
    $updatedCount = 0;
    foreach ($expiredDonations as $donation) {
        $donation->status = 'expired';
        $donation->save();
        $updatedCount++;
    }

    $this->info("Successfully updated {$updatedCount} expired donations.");

    // Optional: Clean up very old expired donations (now changed to 10 days)
    if ($this->confirm('Do you want to clean up expired donations older than 10 days?')) {
        $oldExpiredDonations = Donation::where('status', 'expired')
            ->where('best_before', '<', Carbon::today()->subDays(10))
            ->get();

        if ($oldExpiredDonations->count() > 0) {
            $this->info("Found {$oldExpiredDonations->count()} old expired donations.");
            
            if ($this->confirm('Delete these old expired donations permanently?')) {
                $deletedCount = Donation::where('status', 'expired')
                    ->where('best_before', '<', Carbon::today()->subDays(10))
                    ->delete();
                
                $this->info("Deleted {$deletedCount} old expired donations.");
            } else {
                $this->info('Skipped deletion of old expired donations.');
            }
        } else {
            $this->info('No old expired donations found to clean up.');
        }
    }

    $this->info('Cleanup completed successfully!');
})->purpose('Mark expired donations as expired and clean up old records');

// Schedule the cleanup to run daily
Schedule::command('donations:cleanup-expired')->daily()->at('02:00');

// Command to generate donation statistics
Artisan::command('donations:stats', function () {
    $total = Donation::count();
    $available = Donation::where('status', 'available')->count();
    $reserved = Donation::where('status', 'reserved')->count();
    $completed = Donation::where('status', 'completed')->count();
    $expired = Donation::where('status', 'expired')->count();
    
    $this->info('=== FoodBridge Donation Statistics ===');
    $this->table(
        ['Status', 'Count', 'Percentage'],
        [
            ['Total', $total, '100%'],
            ['Available', $available, $total > 0 ? round(($available/$total)*100, 1).'%' : '0%'],
            ['Reserved', $reserved, $total > 0 ? round(($reserved/$total)*100, 1).'%' : '0%'],
            ['Completed', $completed, $total > 0 ? round(($completed/$total)*100, 1).'%' : '0%'],
            ['Expired', $expired, $total > 0 ? round(($expired/$total)*100, 1).'%' : '0%'],
        ]
    );
})->purpose('Display donation statistics');