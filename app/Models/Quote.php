<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    /**
     * Conexión explícita a MongoDB.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Colección en MongoDB.
     *
     * @var string
     */
    protected $collection = 'quotes';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array<int, string>
     */
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

    /**
     * Conversión de tipos de datos (Casting).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issue_date'  => 'date:Y-m-d',
            'valid_until' => 'date:Y-m-d',
            'subtotal'    => 'float',
            'tax'         => 'float',
            'total'       => 'float',
        ];
    }

    /**
     * Relación nativa en MongoDB: Quote -> Client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relación nativa en MongoDB: Quote -> QuoteItem
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}