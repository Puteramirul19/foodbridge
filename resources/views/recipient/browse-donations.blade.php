@extends('layouts.app')

@section('title', 'Browse Donations - FoodBridge')

@section('content')

<div class="container-fluid"> <div class="donations-form-container"> <div class="form-header"> <div class="d-flex justify-content-between align-items-center"> <h2 class="mb-0">Browse Available Donations</h2> <div class="badge bg-primary"> {{ $donations->total() }} Donations Available </div> </div> <p class="text-white-50 mb-0">Find and reserve surplus food in your community</p> </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filter Donations
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('recipient.donations.browse') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search food description"
                                       value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Food Category</label>
                            <select name="food_category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($foodCategories as $key => $category)
                                    <option value="{{ $key }}" 
                                        {{ request('food_category') == $key ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if($donations->isEmpty())
                <div class="card border-0 shadow-sm text-center p-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
                    <h3>No Donations Available</h3>
                    <p class="lead">There are currently no donations matching your search criteria.</p>
                </div>
            @else
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Food Description</th>
                                    <th>Category</th>
                                    <th>Servings</th>
                                    <th>Best Before</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donations as $donation)
                                    <tr>
                                        <td>{{ Str::limit($donation->food_description, 50) }}</td>
                                        <td>
                                            <span class="badge" style="background-color: #6a70ff;">
                                                {{ $foodCategories[$donation->food_category] }}
                                            </span>
                                        </td>
                                        <td>{{ $donation->estimated_servings }}</td>
                                        <td>
                                            @php 
                                                $daysLeft = now()->diffInDays($donation->best_before, false);
                                            @endphp
                                            <span class="{{ $daysLeft <= 1 ? 'text-danger' : 'text-warning' }}">
                                                {{ $donation->best_before->format('d M Y') }}
                                                @if($daysLeft <= 1)
                                                    <small class="d-block">(Expiring Soon)</small>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary reserve-btn" 
                                                    data-donation-id="{{ $donation->id }}">
                                                <i class="fas fa-shopping-basket me-2"></i>Reserve
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        {{ $donations->appends(request()->input())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
{{-- Reservation Modal --}}

<div class="modal fade" id="reservationModal" tabindex="-1"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title">Reserve Donation</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button> </div> <form id="reservationForm" method="POST"> @csrf <div class="modal-body"> <div class="mb-3"> <label class="form-label">Pickup Date</label> <input type="date" name="pickup_date" class="form-control" required min="{{ now()->format('Y-m-d') }}"> </div> <div class="mb-3"> <label class="form-label">Pickup Time</label> <input type="time" name="pickup_time" class="form-control" required> </div> </div> <div class="modal-footer"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> <button type="submit" class="btn btn-primary">Confirm Reservation</button> </div> </form> </div> </div> </div> @endsection
@section('styles')

<style> .donations-form-container { background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); } .form-header { background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 20px; border-top-left-radius: 12px; border-top-right-radius: 12px; } </style>
@endsection

@section('scripts')

<script> document.addEventListener('DOMContentLoaded', function() { const reservationModal = new bootstrap.Modal(document.getElementById('reservationModal')); const reservationForm = document.getElementById('reservationForm'); const reserveButtons = document.querySelectorAll('.reserve-btn'); reserveButtons.forEach(button => { button.addEventListener('click', function() { const donationId = this.dataset.donationId; reservationForm.action = `/recipient/donations/${donationId}/reserve`; reservationModal.show(); }); }); }); </script>
@endsection

