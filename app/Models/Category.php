<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Adaptado para MongoDB
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'categories';

    protected $fillable = [
        'name',
        'icon',
        'is_optional',
    ];

    protected $casts = [
        'is_optional' => 'boolean',
    ];

    /**
     * Relación: Una categoría tiene muchos productos.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}