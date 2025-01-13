<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'type', 'features', 'size', 'capacity', 'available', 'price', 'images',
        'approval_status',
        'admin_feedback',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }
}
