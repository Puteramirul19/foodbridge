<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Donation;
use App\Models\Reservation;
use Carbon\Carbon;

// Cleanup expired donations and remove overdue reservations
Artisan::command('donations:cleanup-expired {--dry-run : Show what would be updated without actually updating}', function () {
    $this->info('Starting expired donations cleanup...');

    // Find donations that are expired and still marked as available
    $expiredAvailable = Donation::where('status', 'available')
        ->where('best_before', '<', Carbon::today())
        ->get();

    // Find reserved donations that are past best before date (overdue pickups)
    $expiredReserved = Donation::where('status', 'reserved')
        ->where('best_before', '<', Carbon::today())
        ->with('reservations')
        ->get();

    $totalToUpdate = $expiredAvailable->count() + $expiredReserved->count();
    $this->info("Found {$totalToUpdate} expired donations to update.");

    if ($this->option('dry-run')) {
        $this->warn('DRY RUN MODE - No changes will be made');
        
        if ($expiredAvailable->count() > 0) {
            $this->info('Available donations to mark as expired:');
            $this->table(
                ['ID', 'Food Description', 'Best Before', 'Days Expired'],
                $expiredAvailable->map(function ($donation) {
                    return [
                        $donation->id,
                        \Str::limit($donation->food_description, 30),
                        $donation->best_before->format('Y-m-d'),
                        Carbon::today()->diffInDays($donation->best_before) . ' days'
                    ];
                })->toArray()
            );
        }

        if ($expiredReserved->count() > 0) {
            $this->info('Reserved donations to mark as expired (overdue pickups will be DELETED):');
            $this->table(
                ['ID', 'Food Description', 'Best Before', 'Reserved By', 'Pickup Date'],
                $expiredReserved->map(function ($donation) {
                    $reservation = $donation->reservations->where('status', 'pending')->first();
                    return [
                        $donation->id,
                        \Str::limit($donation->food_description, 30),
                        $donation->best_before->format('Y-m-d'),
                        $reservation ? $reservation->recipient->name : 'N/A',
                        $reservation ? $reservation->pickup_date->format('Y-m-d') : 'N/A'
                    ];
                })->toArray()
            );
        }
        
        return;
    }

    // Update expired available donations
    $updatedAvailable = 0;
    foreach ($expiredAvailable as $donation) {
        $donation->status = 'expired';
        $donation->save();
        $updatedAvailable++;
    }

    // Update expired reserved donations and DELETE their overdue reservations
    $updatedReserved = 0;
    $deletedReservations = 0;
    foreach ($expiredReserved as $donation) {
        // Mark donation as expired
        $donation->status = 'expired';
        $donation->save();
        $updatedReserved++;

        // DELETE any pending reservations (clean removal, no cancelled status)
        $pendingReservations = $donation->reservations()->where('status', 'pending')->get();
        foreach ($pendingReservations as $reservation) {
            $reservation->delete(); // This will trigger the model event to update donation status
            $deletedReservations++;
        }
    }

    $this->info("Successfully updated {$updatedAvailable} available donations to expired.");
    $this->info("Successfully updated {$updatedReserved} reserved donations to expired.");
    $this->info("Successfully removed {$deletedReservations} overdue reservations.");

    // Optional: Clean up very old expired donations
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
})->purpose('Mark expired donations as expired and remove overdue reservations');

// Command to handle overdue reservations specifically (DELETE them, don't cancel)
Artisan::command('reservations:cleanup-overdue {--dry-run : Show what would be updated without actually updating}', function () {
    $this->info('Checking for overdue reservations...');

    // Find reservations that are past pickup date but still pending
    $overdueReservations = Reservation::where('status', 'pending')
        ->where('pickup_date', '<', Carbon::today())
        ->with(['donation', 'recipient'])
        ->get();

    $this->info("Found {$overdueReservations->count()} overdue reservations.");

    if ($this->option('dry-run')) {
        $this->warn('DRY RUN MODE - No changes will be made');
        
        if ($overdueReservations->count() > 0) {
            $this->table(
                ['Reservation ID', 'Food Description', 'Recipient', 'Pickup Date', 'Days Overdue'],
                $overdueReservations->map(function ($reservation) {
                    return [
                        $reservation->id,
                        \Str::limit($reservation->donation->food_description, 30),
                        $reservation->recipient->name,
                        $reservation->pickup_date->format('Y-m-d'),
                        Carbon::today()->diffInDays($reservation->pickup_date) . ' days'
                    ];
                })->toArray()
            );
        }
        
        return;
    }

    $deletedCount = 0;
    $donationsUpdated = 0;

    foreach ($overdueReservations as $reservation) {
        $donation = $reservation->donation;
        
        // DELETE the reservation (this will trigger model event to update donation status)
        $reservation->delete();
        $deletedCount++;

        // Check if donation status changed
        $donation->refresh();
        $newStatus = $donation->determineStatus();
        
        if ($donation->status !== $newStatus) {
            $donation->status = $newStatus;
            $donation->save();
            $donationsUpdated++;
        }
    }

    $this->info("Successfully removed {$deletedCount} overdue reservations.");
    $this->info("Successfully updated {$donationsUpdated} donation statuses.");
})->purpose('Remove overdue reservations that were not picked up');

// Schedule the cleanup commands to run daily
Schedule::command('donations:cleanup-expired')->daily()->at('02:00');
Schedule::command('reservations:cleanup-overdue')->daily()->at('02:30');

// Command to generate donation statistics (simplified)
Artisan::command('donations:stats', function () {
    $total = Donation::count();
    $available = Donation::where('status', 'available')->count();
    $reserved = Donation::where('status', 'reserved')->count();
    $completed = Donation::where('status', 'completed')->count();
    $expired = Donation::where('status', 'expired')->count();
    
    // Reservation statistics (only pending and completed now)
    $totalReservations = Reservation::count();
    $pendingReservations = Reservation::where('status', 'pending')->count();
    $completedReservations = Reservation::where('status', 'completed')->count();
    
    $this->info('=== FoodBridge Donation Statistics ===');
    $this->table(
        ['Status', 'Count', 'Percentage'],
        [
            ['Total Donations', $total, '100%'],
            ['Available', $available, $total > 0 ? round(($available/$total)*100, 1).'%' : '0%'],
            ['Reserved', $reserved, $total > 0 ? round(($reserved/$total)*100, 1).'%' : '0%'],
            ['Completed', $completed, $total > 0 ? round(($completed/$total)*100, 1).'%' : '0%'],
            ['Expired', $expired, $total > 0 ? round(($expired/$total)*100, 1).'%' : '0%'],
        ]
    );

    $this->info('=== FoodBridge Reservation Statistics ===');
    $this->table(
        ['Status', 'Count', 'Percentage'],
        [
            ['Total Reservations', $totalReservations, '100%'],
            ['Pending', $pendingReservations, $totalReservations > 0 ? round(($pendingReservations/$totalReservations)*100, 1).'%' : '0%'],
            ['Completed', $completedReservations, $totalReservations > 0 ? round(($completedReservations/$totalReservations)*100, 1).'%' : '0%'],
        ]
    );

    // Additional insights
    $totalServings = Donation::sum('estimated_servings');
    $completedServings = Donation::where('status', 'completed')->sum('estimated_servings');
    $this->info("Total Food Servings: {$totalServings}");
    $this->info("Completed Food Servings: {$completedServings}");
    
})->purpose('Display donation and reservation statistics');