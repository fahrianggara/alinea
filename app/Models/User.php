<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Protect the id from mass assignment
    protected $guarded = ['id'];
    protected $appends = ['image_url'];

    // Specify which attributes should be hidden
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Allow mass assignment for these attributes
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'role',
        'password',
        'nim',
    ];

    // Relationship with Admin model
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    // Relationship with Borrowing model
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    // Automatically hash the password when it is set
    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getImageUrlAttribute()
    {
        return asset("storage/$this->image");
    }
}

