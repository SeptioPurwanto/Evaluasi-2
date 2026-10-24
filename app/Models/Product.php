<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    public const SIZES = ['39', '40', '41', '42', '43'];

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name', 'description', 'category_id', 'price', 'img', 'stock', 'slug',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'stock' => 'integer',
        ];
    }

    // Satu produk dimiliki satu kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Gambar bisa berupa URL penuh (seeder) atau path hasil upload (storage/public)
    public function hasLocalImage(): bool
    {
        return $this->img && ! Str::startsWith($this->img, ['http://', 'https://']);
    }

    public function deleteLocalImage(): void
    {
        if ($this->hasLocalImage()) {
            Storage::disk('public')->delete($this->img);
        }
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->img) {
            return 'data:image/svg+xml;utf8,' . rawurlencode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="450"><rect width="100%" height="100%" fill="#e0f2fe"/><text x="50%" y="50%" font-size="64" text-anchor="middle" dominant-baseline="middle">👟</text></svg>'
            );
        }

        return $this->hasLocalImage() ? asset('storage/' . $this->img) : $this->img;
    }

    public function getPriceRpAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }
}
