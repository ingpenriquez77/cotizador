<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Client extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'clients';

    protected $fillable = [
        'business_name',
        'contact_name',
        'phone',
        'email',
        'status',
        'address',
        'rfc',
    ];
}