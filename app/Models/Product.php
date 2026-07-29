<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'brand',
        'description',
        'cost_price',
        'has_margin',
        'supplier_link',
    ];

    protected $attributes = [
        'has_margin' => true,
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'float',
            'has_margin' => 'boolean',
        ];
    }

    /**
     * Accesor para calcular la ganancia en dinero ($)
     */
    public function getMarginAmountAttribute(): float
    {
        if ($this->has_margin) {
            return round($this->cost_price * 0.20, 2);
        }

        return 0.00;
    }

    /**
     * Accesor para calcular el precio final de venta ($)
     */
    public function getSellingPriceAttribute(): float
    {
        if ($this->has_margin) {
            return round($this->cost_price * 1.20, 2);
        }

        return round($this->cost_price, 2);
    }

    // Alias para compatibilidad de vistas
    public function getSuggestedSalePriceAttribute(): float
    {
        return $this->getSellingPriceAttribute();
    }
}