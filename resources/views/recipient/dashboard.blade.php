@extends('layouts.app')

@section('title', 'Recipient Dashboard')

@section('content')

<div class="container-fluid"> <div class="donations-form-container"> <div class="form-header"> <div class="d-flex justify-content-between align-items-center"> <h2 class="mb-0">Recipient Dashboard</h2> <div class="d-flex align-items-center"> <span class="badge bg-light text-dark me-2"> {{ $stats['totalReservations'] }} Total Food Donations </span> </div> </div> <p class="text-white-50 mb-0">Track your food donations and manage pickups</p> </div>
    <div class="row p-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Overview
                    </h4>
                    <span class="badge bg-primary">{{ Auth::user()->role }}</span>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <h3>{{ Auth::user()->name }}</h3>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <h4 class="text-primary">{{ $stats['totalReservations'] }}</h4>
                            <small class="text-muted">Total Donations</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $stats['activeReservations'] }}</h4>
                            <small class="text-muted">Pending Pickups</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success">{{ $stats['completedReservations'] }}</h4>
                            <small class="text-muted">Collected</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('recipient.donations.browse') }}" class="btn btn-outline-primary">
                            <i class="fas fa-utensils me-2"></i>Browse Donations
                        </a>
                        <a href="{{ route('recipient.reservations') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list-alt me-2"></i>My Reservations
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-shopping-basket me-2"></i>Recent Reservations
                    </h4>
                    <a href="{{ route('recipient.reservations') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                
                @if($reservations->isEmpty())
                    <div class="card-body text-center">
                        <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                        <h3>No Donations Yet</h3>
                        <p>Start by browsing available donations in your area.</p>
                        <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Browse Donations
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Food Description</th>
                                    <th>Donor</th>
                                    <th>Pickup Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $reservation)
                                    <tr>
                                        <td>
                                            {{ Str::limit($reservation->donation->food_description, 30) }}
                                            <small class="d-block text-muted">
                                                {{ ucfirst(str_replace('_', ' ', $reservation->donation->food_category)) }}
                                            </small>
                                        </td>
                                        <td>{{ $reservation->donation->donor->name }}</td>
                                        <td>
                                            <i class="fas fa-calendar me-2"></i>
                                            {{ $reservation->pickup_date->format('d M Y') }}
                                        </td>
                                        <td>
                                            <span class="badge 
                                            {{ $reservation->status == 'pending' ? 'bg-warning' : 
                                               ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('recipient.reservations.details', $reservation) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        {{ $reservations->links() }}
                        <a href="{{ route('recipient.donations.browse') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-2"></i>New Donation
                        </a>
                    </div>
                @endif
            </div>

            {{-- Quick Stats --}}
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check text-primary fa-3x mb-3"></i>
                            <h3>{{ $stats['activeReservations'] }}</h3>
                            <p class="text-muted">Pending Pickups</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h3>{{ $stats['completedReservations'] }}</h3>
                            <p class="text-muted">Successfully Collected</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils text-warning fa-3x mb-3"></i>
                            <h3>{{ $stats['totalReservations'] }}</h3>
                            <p class="text-muted">Total Food Donations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> @endsection
@section('styles')

<style> .donations-form-container { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); } .form-header { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; } </style>
@endsection

