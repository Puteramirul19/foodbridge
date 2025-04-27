@extends('layouts.app')

@section('title', 'My Reservations - FoodBridge')

@section('content')

<div class="container-fluid"> <div class="donations-form-container"> <div class="form-header"> <div class="d-flex justify-content-between align-items-center"> <h2 class="mb-0">My Reservations</h2> <div class="badge bg-primary"> {{ $reservations->total() }} Reservations </div> </div> <p class="text-white-50 mb-0">Track and manage your food donations</p> </div>
    @if($reservations->isEmpty())
        <div class="card border-0 text-center p-5">
            <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
            <h3>No Reservations Yet</h3>
            <p class="lead">Start by browsing available donations</p>
            <div>
                <a href="{{ route('recipient.donations.browse') }}" class="btn btn-primary">
                    <i class="fas fa-utensils me-2"></i>Browse Donations
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Food Description</th>
                            <th>Donor</th>
                            <th>Pickup Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>
                                    {{ Str::limit($reservation->donation->food_description, 50) }}
                                    <small class="d-block text-muted">
                                        {{ $reservation->donation->food_category }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user me-2"></i>
                                        {{ $reservation->donation->donor->name }}
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $reservation->pickup_date->format('d M Y') }}
                                    <small class="d-block">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $reservation->pickup_time }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge 
                                    {{ $reservation->status == 'pending' ? 'bg-warning' : 
                                       ($reservation->status == 'completed' ? 'bg-success' : 'bg-secondary') }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('recipient.reservations.details', $reservation) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($reservation->status == 'pending')
                                            <form action="{{ route('recipient.reservations.cancel', $reservation) }}" 
                                                  method="POST" class="d-inline cancel-reservation-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer d-flex justify-content-between align-items-center">
                {{ $reservations->links() }}
                <a href="{{ route('recipient.donations.browse') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i>New Reservation
                </a>
            </div>
        </div>
    @endif
</div>
</div> @endsection
@section('styles')

<style> .donations-form-container { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); } .form-header { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; } </style>
@endsection

@section('scripts')

<script> document.addEventListener('DOMContentLoaded', function() { // Confirm reservation cancellation const cancelForms = document.querySelectorAll('.cancel-reservation-form'); cancelForms.forEach(form => { form.addEventListener('submit', function(e) { const confirmCancel = confirm('Are you sure you want to cancel this reservation?'); if (!confirmCancel) { e.preventDefault(); } }); }); }); </script>
@endsection

