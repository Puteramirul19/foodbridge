@extends('layouts.app')

@section('title', 'Recipient Dashboard')

@section('content')
<div class="recipient-dashboard">
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    
    <div class="dashboard-actions">
        <a href="{{ route('recipient.donations.browse') }}" class="btn">Browse Donations</a>
    </div>
    
    <section class="my-reservations">
        <h2>My Reservations</h2>
        @if($reservations->isEmpty())
            <p>You haven't made any reservations yet.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Food Name</th>
                        <th>Donor</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->donation->food_name }}</td>
                        <td>{{ $reservation->donation->donor->name }}</td>
                        <td>{{ $reservation->status }}</td>
                        <td>
                            <a href="#" class="btn">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
</div>
@endsection