<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransaction extends Model
{
    use SoftDeletes;

    // Constants untuk type
    public const TYPE_IN  = 'Masuk';
    public const TYPE_OUT = 'Keluar';

    // Constants untuk status
    public const STATUS_PENDING     = 'Pending';
    public const STATUS_IN_PROGRESS = 'In Progress';
    public const STATUS_COMPLETED   = 'Completed';
    public const STATUS_APPROVED    = 'Diterima';
    public const STATUS_REJECTED    = 'Ditolak';
    public const STATUS_DISPATCHED  = 'Dikeluarkan';

    protected $fillable = [
        'reference',
        'product_id',
        'supplier_id',
        'user_id',
        'assigned_by',
        'assigned_to',
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
        'date'      => 'date',
        'quantity'  => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    // Relasi
    public function product()     { return $this->belongsTo(Product::class); }
    public function supplier()    { return $this->belongsTo(Supplier::class); }
    public function user()        { return $this->belongsTo(User::class, 'user_id'); }
    public function approver()    { return $this->belongsTo(User::class, 'approved_by'); }
    public function assignedBy()  { return $this->belongsTo(User::class, 'assigned_by'); }
    public function assignedTo()  { return $this->belongsTo(User::class, 'assigned_to'); }

    // Koreksi
    public function correctedFrom() { return $this->belongsTo(StockTransaction::class, 'correction_of'); }
    public function corrections()   { return $this->hasMany(StockTransaction::class, 'correction_of'); }

    // Accessor
    public function getIsCorrectableAttribute(): bool
    {
        return $this->type === self::TYPE_IN
            && $this->status === self::STATUS_APPROVED
            && !$this->corrections()->exists();
    }

    // public function createdTransactions()
    // {
    //     return $this->hasMany(StockTransaction::class, 'user_id');
    // }

    // public function assignedTransactions()
    // {
    //     return $this->hasMany(StockTransaction::class, 'assigned_to');
    // }

    // public function approvedTransactions()
    // {
    //     return $this->hasMany(StockTransaction::class, 'approved_by');
    // }
}