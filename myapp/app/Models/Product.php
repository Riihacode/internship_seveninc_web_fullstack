<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    // use SoftDeletes;

    // protected $fillable = [
    //     'category_id', 
    //     'supplier_id', 
    //     'name', 
    //     'sku', 
    //     'description',
    //     'purchase_price', 
    //     'selling_price', 
    //     'image',
    //     'minimum_stock', 
    //     'current_stock',
    // ];

    // public function attributes() 
    // {
    //     return $this->hasMany(ProductAttribute::class);
    // }

    // public function transactions() 
    // {
    //     return $this->hasMany(StockTransaction::class);
    // }

    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'sku',
        'description',
        'purchase_price',
        'selling_price',
        'image',
        'minimum_stock',
        'current_stock',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price'  => 'decimal:2',
        'minimum_stock'  => 'integer',
        'current_stock'  => 'integer',
    ];

    // Relasi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
