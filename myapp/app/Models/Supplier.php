<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    //
    // use HasFactory;

    // protected $fillable = [
    //     'name',
    //     'address',
    //     'phone',
    //     'email',
    // ];

    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    // 🔹 Relasi
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
