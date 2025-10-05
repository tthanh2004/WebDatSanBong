<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'ma_khach_hang',
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', '_id');
    }
}
