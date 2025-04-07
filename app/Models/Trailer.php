<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;



class Trailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'type', 'features', 'size',
        'trailer_weight', 'max_payload', 'connector_type', 'trailer_brakes',
        'hitch_ball_size', 'price', 'location', 'images', 'approval_status'
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reviews()
{
    return $this->hasMany(Review::class, 'trailer_id');
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
