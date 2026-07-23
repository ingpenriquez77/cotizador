<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'client_id',
        'subtotal',
        'tax',
        'total',
        'status',
        'issue_date',
        'valid_until',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date:Y-m-d',
        'valid_until' => 'date:Y-m-d',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }
}
