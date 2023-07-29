<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class service extends Model
{
    use HasFactory,Billable;

    protected $fillable = [
        'user_id',
        'category_id',
        'price',
        'title',
        'image',
        'description',
        'accept_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
