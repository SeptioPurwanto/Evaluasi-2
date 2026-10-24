<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Primary key bukan 'id'
    protected $primaryKey = 'category_id';

    protected $fillable = ['category'];

    // Satu kategori punya banyak produk
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
