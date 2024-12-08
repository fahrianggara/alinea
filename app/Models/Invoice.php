<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'invoice_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'invoice_id');
    }
}
