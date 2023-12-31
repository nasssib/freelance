<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class massage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'text'
    ];

    protected $hidden = [
        'updated_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
