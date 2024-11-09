<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['cover_url'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCoverUrlAttribute()
    {
        return asset("storage/$this->cover");
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
