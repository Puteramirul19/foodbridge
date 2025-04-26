<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model {
    protected $fillable = [
        'user_id', // donor who created the donation
        'food_category',
        'food_description',
        'estimated_servings',
        'best_before',
        'donation_type',
        'pickup_location',
        'additional_instructions',
        'contact_number',
        'status' // e.g., available, reserved, completed
    ];

    public function donor() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient() {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}