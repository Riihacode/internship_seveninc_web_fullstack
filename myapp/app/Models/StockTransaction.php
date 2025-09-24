<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{
    use SoftDeletes; // 🔹 optional tapi best practice untuk audit trail

    // Constants untuk type
    public const TYPE_IN  = 'Masuk';
    public const TYPE_OUT = 'Keluar';

    // Constants untuk status
    public const STATUS_PENDING    = 'Pending';
    public const STATUS_APPROVED   = 'Diterima';
    public const STATUS_DISPATCHED = 'Dikeluarkan';
    public const STATUS_REJECTED   = 'Ditolak';

    protected $fillable = [
        'reference',
        'product_id',
        'supplier_id',   // ← ini harus ditambah
        'user_id',
        'approved_by',
        'type',
        'quantity',
        'unit_cost',
        'date',
        'status',
        'notes',
        'correction_of',
    ];

    protected $casts = [
        'date'     => 'date', // cukup 'date', otomatis Carbon
        'quantity' => 'integer',
        'unit_cost'=> 'decimal:2',
    ];

    // 🔹 Relasi Eloquent
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Koreksi salah input doleh manager
    public function correctedFrom()
    {
        return $this->belongsTo(StockTransaction::class, 'correction_of');
    }

    public function corrections()
    {
        return $this->hasMany(StockTransaction::class, 'correction_of');
    }
    // App\Models\StockTransaction.php

    public function getIsCorrectableAttribute(): bool
    {
        return $this->type === self::TYPE_IN
            && $this->status === self::STATUS_APPROVED
            && is_null($this->correction_of)
            && !$this->corrections()->exists();
    }

}
