@extends('layouts.app')

@section('title', 'Browse Donations - FoodBridge')

@section('content')

<div class="container-fluid"> <div class="donations-form-container"> <div class="form-header"> <div class="d-flex justify-content-between align-items-center"> <h2 class="mb-0">Browse Available Donations</h2> <div class="badge bg-primary"> {{ $donations->total() }} Donations Available </div> </div> <p class="text-white-50 mb-0">Find and accept surplus food in your community</p> </div>
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
                                    <th>Location</th>
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
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            {{ Str::limit($donation->pickup_location, 30) }}
                                        </td>
                                        <td>
                                            @php 
                                                $bestBefore = is_string($donation->best_before) 
                                                    ? \Carbon\Carbon::parse($donation->best_before) 
                                                    : $donation->best_before;
                                                
                                                $daysLeft = now()->diffInDays($bestBefore, false);
                                            @endphp
                                            <span class="{{ $daysLeft <= 1 ? 'text-danger' : 'text-warning' }}">
                                                {{ $bestBefore->format('d M Y') }}
                                                @if($daysLeft <= 1)
                                                    <small class="d-block">(Expiring Soon)</small>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary view-donation-btn"
                                                        data-donation-details='@json([
                                                            "id" => $donation->id,
                                                            "food_description" => $donation->food_description,
                                                            "food_category" => $foodCategories[$donation->food_category],
                                                            "estimated_servings" => $donation->estimated_servings,
                                                            "best_before" => $bestBefore->format('d M Y'),
                                                            "donation_type" => ucfirst($donation->donation_type),
                                                            "pickup_location" => $donation->pickup_location,
                                                            "contact_number" => $donation->contact_number,
                                                            "additional_instructions" => $donation->additional_instructions,
                                                            "donor" => [
                                                                "name" => $donation->donor->name,
                                                                "email" => $donation->donor->email,
                                                                "phone" => $donation->contact_number,
                                                                "role" => ucfirst($donation->donor->role),
                                                                "bio" => "A local donor committed to reducing food waste and supporting the community.",
                                                                "avatar" => null,
                                                                "total_donations" => 25,
                                                                "total_servings" => 500,
                                                                "rating" => 4.7,
                                                                "impact_percentage" => 75
                                                            ]
                                                        ])'>
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success accept-donation-btn"
                                                        data-donation-id="{{ $donation->id }}">
                                                    <i class="fas fa-shopping-basket"></i>
                                                </button>
                                            </div>
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
{{-- Include the Donation Details Modal --}}
@include('recipient.donation-details-modal')
@endsection

@section('scripts')

<script> document.addEventListener('DOMContentLoaded', function() { // Replace 'claim-donation-btn' with 'accept-donation-btn' const acceptButtons = document.querySelectorAll('.accept-donation-btn'); const pickupRequestForm = document.getElementById('pickupRequestForm'); acceptButtons.forEach(button => { button.addEventListener('click', function() { const donationId = this.dataset.donationId; pickupRequestForm.action = `/recipient/donations/${donationId}/reserve`; new bootstrap.Modal(document.getElementById('pickupRequestModal')).show(); }); }); }); </script>
@endsection

