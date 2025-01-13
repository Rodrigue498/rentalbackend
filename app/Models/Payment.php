<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'transaction_id',
        'amount',
        'service_fee',
        'owner_payout',
        'status',
        'payment_method',
    ];

    /**
     * Get the booking associated with the payment.
     */
    public function booking()
    {
        return $this->belongsTo(Bookings::class);
    }

    /**
     * Get the user who made the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
