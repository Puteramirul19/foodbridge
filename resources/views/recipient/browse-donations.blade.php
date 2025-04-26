@extends('layouts.app')

@section('title', 'Browse Donations')

@section('content')
<div class="browse-donations">
    <h1>Available Donations</h1>
    
    <div class="filters">
        <form method="GET" action="{{ route('recipient.donations.browse') }}">
            <input type="text" name="search" placeholder="Search food...">
            <select name="food_type">
                <option value="">All Types</option>
                <option value="perishable">Perishable</option>
                <option value="non-perishable">Non-Perishable</option>
                <option value="prepared_meals">Prepared Meals</option>
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>
    
    @if($donations->isEmpty())
        <p>No donations are currently available.</p>
    @else
        <div class="donations-grid">
            @foreach($donations as $donation)
            <div class="donation-card">
                <h3>{{ $donation->food_name }}</h3>
                <p>Type: {{ ucfirst(str_replace('_', ' ', $donation->food_type)) }}</p>
                <p>Quantity: {{ $donation->quantity }}</p>
                <p>Pickup Location: {{ $donation->pickup_location }}</p>
                <p>Urgency: {{ ucfirst($donation->urgency_level) }}</p>
                
                <form method="POST" action="{{ route('recipient.donations.reserve', $donation) }}">
                    @csrf
                    <button type="submit" class="btn">Reserve</button>
                </form>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection