<?php

namespace App\Observers;

use App\Models\StockTransaction;

class StockTransactionObserver
{
    /**
     * Handle the StockTransaction "created" event.
     */
    public function created(StockTransaction $stockTransaction): void
    {
        //
    }

    /**
     * Handle the StockTransaction "updated" event.
     */
    // public function updated(StockTransaction $stockTransaction): void
    // {
    //     //
    // }
    public function updated(StockTransaction $stockTransaction): void
    {
        if (!$stockTransaction->wasChanged('status')) return;

        $prev   = $stockTransaction->getOriginal('status');
        $now    = $stockTransaction->status;

        // APPROVE MASUK: Pending -> Diterima => +quantity
        if ($stockTransaction->type === StockTransaction::TYPE_IN
            && $prev                !== StockTransaction::STATUS_APPROVED
            && $now                 === StockTransaction::STATUS_APPROVED
        ) {
            $stockTransaction->product()->increment('current_stock', $stockTransaction->quantity);
        }

        // DISPATCH KELUAR: Pending -> Dikeluarkan => -quantity (pastikan tidak negatif di controller)
        if ($stockTransaction->type === StockTransaction::TYPE_OUT
            && $prev                !== StockTransaction::STATUS_DISPATCHED
            && $now                 === StockTransaction::STATUS_DISPATCHED
        ) {
            $stockTransaction->product()->decrement('current_stock', $stockTransaction->quantity);
        }
    }


    /**
     * Handle the StockTransaction "deleted" event.
     */
    public function deleted(StockTransaction $stockTransaction): void
    {
        //
    }

    /**
     * Handle the StockTransaction "restored" event.
     */
    public function restored(StockTransaction $stockTransaction): void
    {
        //
    }

    /**
     * Handle the StockTransaction "force deleted" event.
     */
    public function forceDeleted(StockTransaction $stockTransaction): void
    {
        //
    }
}
