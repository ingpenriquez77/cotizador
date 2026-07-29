<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class QuoteItem extends Model
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
    protected $collection = 'quote_items';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quote_id',
        'product_id',
        'concept',
        'quantity',
        'cost_price',
        'margin_percentage',
        'unit_price',
        'subtotal',
    ];

    /**
     * Valores por defecto para los atributos.
     *
     * @var array
     */
    protected $attributes = [
        'quantity'          => 1,
        'margin_percentage' => 0.00,
    ];

    /**
     * Conversión de tipos de datos (Casting).
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity'          => 'integer',
            'cost_price'        => 'float',
            'margin_percentage' => 'float',
            'unit_price'        => 'float',
            'subtotal'          => 'float',
        ];
    }

    /**
     * Relación: QuoteItem -> Quote
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    /**
     * Relación: QuoteItem -> Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}