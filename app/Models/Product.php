<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'cost_price',
        'has_margin',
        'supplier_link',
        'description',
    ];

    protected $casts = [
        'has_margin' => 'boolean',
    ];

    /**
     * Accesor para calcular dinámicamente el precio sugerido de venta.
     */
    public function getSuggestedSalePriceAttribute()
    {
        return $this->has_margin ? ($this->cost_price * 1.30) : $this->cost_price;
    }
}
