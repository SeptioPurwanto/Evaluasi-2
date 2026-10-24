<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = ['baru', 'diproses', 'selesai'];

    protected $primaryKey = 'order_id';

    protected $fillable = ['user_id', 'name', 'phone', 'address', 'note', 'total', 'status'];

    protected function casts(): array
    {
        return ['total' => 'float'];
    }

    // Satu pesanan punya banyak item
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    // Pesanan dimiliki satu user (boleh kosong untuk tamu)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCodeAttribute(): string
    {
        return 'SL-' . str_pad((string) $this->order_id, 5, '0', STR_PAD_LEFT);
    }

    public function getTotalRpAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total, 0, ',', '.');
    }
}
