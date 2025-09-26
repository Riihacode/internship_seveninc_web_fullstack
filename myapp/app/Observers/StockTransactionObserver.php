<?php

namespace App\Observers;

use App\Models\StockTransaction;

class StockTransactionObserver
{
    
    /**
     * Handle the "created" event.
     */
    public function created(StockTransaction $transaction): void
    {
        // Tidak perlu apa-apa → stok hanya berubah ketika status update
    }

    /**
     * Handle the "updated" event.
     * Sinkronisasi stok saat status berubah.
     */
    public function updated(StockTransaction $transaction): void
    {
        if (!$transaction->wasChanged('status')) {
            return;
        }

        $prev = $transaction->getOriginal('status');
        $now  = $transaction->status;

        // ✅ APPROVE MASUK → tambah stok
        if (
            $transaction->type === StockTransaction::TYPE_IN &&
            $prev !== StockTransaction::STATUS_APPROVED &&
            $now  === StockTransaction::STATUS_APPROVED
        ) {
            $transaction->product()->increment('current_stock', $transaction->quantity);
        }

        // ✅ DISPATCH KELUAR → kurangi stok
        if (
            $transaction->type === StockTransaction::TYPE_OUT &&
            $prev !== StockTransaction::STATUS_DISPATCHED &&
            $now  === StockTransaction::STATUS_DISPATCHED
        ) {
            $transaction->product()->decrement('current_stock', $transaction->quantity);
        }

        // ❌ Kalau status rollback (Diterima → Ditolak), bisa ditambah logika restore stok di sini
    }

    /**
     * Handle the "deleted" event.
     * Rollback stok jika transaksi dihapus.
     */
    public function deleted(StockTransaction $transaction): void
    {
        if ($transaction->status === StockTransaction::STATUS_APPROVED 
            && $transaction->type === StockTransaction::TYPE_IN
        ) {
            // Hapus transaksi MASUK yang sudah approve → rollback stok
            $transaction->product()->decrement('current_stock', $transaction->quantity);
        }

        if ($transaction->status === StockTransaction::STATUS_DISPATCHED 
            && $transaction->type === StockTransaction::TYPE_OUT
        ) {
            // Hapus transaksi KELUAR yang sudah dispatched → rollback stok
            $transaction->product()->increment('current_stock', $transaction->quantity);
        }
    }

    public function restored(StockTransaction $transaction): void
    {
        // Optional: kalau mau restore stok saat transaksi di-restore
    }

    public function forceDeleted(StockTransaction $transaction): void
    {
        // Tidak perlu, sama saja dengan deleted()
    }
}
