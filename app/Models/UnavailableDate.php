<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'trailer_id',
        'date',
    ];

    public function trailer()
    {
        return $this->belongsTo(Trailer::class);
    }
}
