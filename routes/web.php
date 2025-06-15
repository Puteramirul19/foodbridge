<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\RecipientController;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

// Public Routes - Updated with real data
Route::get('/', function () {
    // Get real statistics from database
    $totalServings = \App\Models\Donation::sum('estimated_servings') ?: 0;
    $activeDonors = \App\Models\User::where('role', 'donor')
                                    ->where('is_active', true)
                                    ->whereHas('donations')
                                    ->count();
    $activeRecipients = \App\Models\User::where('role', 'recipient')
                                        ->where('is_active', true)
                                        ->whereHas('reservations')
                                        ->count();
    
    return view('welcome', compact(
        'totalServings', 
        'activeDonors', 
        'activeRecipients'
    ));
})->name('home');

// Authentication Routes
Route::prefix('auth')->group(function () {
    // Registration Routes
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Donor Routes (protected)
Route::middleware(['auth', 'active', 'role:donor'])->prefix('donor')->name('donor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DonorController::class, 'dashboard'])->name('dashboard');
    
    // Pending Pickups Management
    Route::get('/pending-pickups', [DonorController::class, 'pendingPickups'])->name('pending-pickups');
    Route::post('/confirm-pickup/{reservation}', [ReservationController::class, 'confirmPickup'])->name('confirm-pickup');
    Route::post('/mark-not-collected/{reservation}', [ReservationController::class, 'markNotCollected'])->name('mark-not-collected');
    
    // REMOVED: Donation Insights route completely

    // Existing Donation Routes
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/donations/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');
    Route::put('/donations/{donation}', [DonationController::class, 'update'])->name('donations.update');
    Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
    Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
});

// Recipient Routes (protected)
Route::middleware(['auth', 'active', 'role:recipient'])->prefix('recipient')->name('recipient.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [RecipientController::class, 'dashboard'])->name('dashboard');
    // Donations Browsing
    Route::get('/donations/browse', [RecipientController::class, 'browseDonations'])->name('donations.browse');

    // Reservations
    Route::get('/reservations', [RecipientController::class, 'listReservations'])->name('reservations');
    Route::get('/reservations/{reservation}', [RecipientController::class, 'showReservationDetails'])
        ->name('reservations.details');
    Route::delete('/reservations/{reservation}/cancel', [RecipientController::class, 'cancelReservation'])
        ->name('reservations.cancel');

    // Reserve a specific donation
    Route::post('/donations/{donation}/accept', [ReservationController::class, 'store'])
        ->name('donations.accept');
});

// Admin Routes (protected)
Route::middleware(['auth', 'active', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // User Management
    Route::get('/users', [AdminController::class, 'manageUsers'])->name('users.index');
    Route::put('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    
    // Report Generation Routes
    Route::get('/generate-reports', [AdminController::class, 'showReportForm'])->name('show-reports');
    Route::post('/generate-reports', [AdminController::class, 'generateReports'])->name('generate-reports');
});

// Profile Routes 
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});