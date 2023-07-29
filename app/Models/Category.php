<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =['name','image'];

    /* protected static function booted () {
        static::deleting(function(User $user) { // before delete() method call this
             $user->photos()->delete();
             // do the rest of the cleanup...
        });
    } */
    public function services()
    {
        return $this->hasMany(service::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class,'category_id');
    }
}
