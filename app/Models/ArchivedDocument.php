<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'document_name', 'file_path', 'archived_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
