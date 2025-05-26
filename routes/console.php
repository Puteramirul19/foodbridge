<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Donation;
use Carbon\Carbon;

// Existing inspire command
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cleanup expired donations command
Artisan::command('donations:cleanup-expired {--dry-run : Show what would be updated without actually updating}', function () {
    $this->info('Starting expired donations cleanup...');

    // Find donations that are expired but still marked as available
    $expiredDonations = Donation::where('status', 'available')
        ->where('best_before', '<', Carbon::today())
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

    // Optional: Clean up very old expired donations (e.g., older than 30 days)
    if ($this->confirm('Do you want to clean up expired donations older than 30 days?')) {
        $oldExpiredDonations = Donation::where('status', 'expired')
            ->where('best_before', '<', Carbon::today()->subDays(30))
            ->get();

        if ($oldExpiredDonations->count() > 0) {
            $this->info("Found {$oldExpiredDonations->count()} old expired donations.");
            
            if ($this->confirm('Delete these old expired donations permanently?')) {
                $deletedCount = Donation::where('status', 'expired')
                    ->where('best_before', '<', Carbon::today()->subDays(30))
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

// Additional useful commands for your FoodBridge application

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
    
    // Show expiring soon donations
    $expiringSoon = Donation::where('status', 'available')
        ->whereBetween('best_before', [Carbon::today(), Carbon::today()->addDays(2)])
        ->count();
    
    if ($expiringSoon > 0) {
        $this->warn("⚠️  {$expiringSoon} donations are expiring within 2 days!");
    }
})->purpose('Display donation statistics');

// Command to send notifications about expiring donations
Artisan::command('donations:notify-expiring', function () {
    $expiringSoon = Donation::with('donor')
        ->where('status', 'available')
        ->whereBetween('best_before', [Carbon::today(), Carbon::today()->addDays(1)])
        ->get();

    if ($expiringSoon->isEmpty()) {
        $this->info('No donations expiring soon.');
        return;
    }

    $this->info("Found {$expiringSoon->count()} donations expiring within 1 day:");
    
    $this->table(
        ['ID', 'Donor', 'Food Description', 'Best Before', 'Servings'],
        $expiringSoon->map(function ($donation) {
            return [
                $donation->id,
                $donation->donor->name,
                \Str::limit($donation->food_description, 30),
                $donation->best_before->format('Y-m-d'),
                $donation->estimated_servings
            ];
        })->toArray()
    );

    // Here you could add email notifications to donors or recipients
    $this->info('Notification logic would go here (email/SMS to donors and recipients)');
})->purpose('Notify about donations expiring soon');

// Schedule notifications to run twice daily
Schedule::command('donations:notify-expiring')->twiceDaily(9, 17);