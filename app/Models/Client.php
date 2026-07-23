<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'rfc',
        'status',
    ];
}
