<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreStockTransactionRequest;
use App\Http\Requests\UpdateStockTransactionRequest;

class StockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = StockTransaction::with(['product','supplier','user','approver','assignedBy','assignedTo','corrections'])
            ->when($request->q, fn($q,$v) =>
                $q->whereHas('product', fn($p) => $p->where('name','like',"%$v%"))
                  ->orWhereHas('user', fn($u) => $u->where('name','like',"%$v%"))
            )
            ->when($request->status, fn($q,$v) => $q->where('status',$v))
            ->orderByDesc('id')
            ->paginate(10);

        // gunakan accessor dari model
        foreach ($transactions as $t) {
            $t->can_correct = $t->is_correctable;
        }

        return view('transactions.index', compact('transactions'));
    }

    // public function create()
    // {
    //     $products = Product::all();

    //     if (auth()->user()->role === 'Manajer Gudang') {
    //         $suppliers = Supplier::all();
    //         return view('transactions.create_manager', compact('products', 'suppliers'));
    //     }

    //     return view('transactions.create_staff', compact('products'));
    // }
    // public function create()
    // {
    //     $products = Product::all();

    //     if (auth()->user()->role === 'Manajer Gudang') {
    //         $suppliers = Supplier::all();
    //         return view('transactions.create_manager', compact('products', 'suppliers'));
    //     }

    //     if (auth()->user()->role === 'Admin') {
    //         $suppliers = Supplier::all();
    //         return view('transactions.create_admin', compact('products', 'suppliers'));
    //     }

    //     // default Staff
    //     return view('transactions.create_staff', compact('products'));
    // }
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('transactions.create', compact('products', 'suppliers'));
    }


    // public function store(StoreStockTransactionRequest $request)
    // {
    //     $data = $request->validated();
    //     $data['user_id']     = Auth::id();
    //     $data['assigned_by'] = Auth::id();
    //     $data['assigned_to'] = $request->assigned_to ?? null;
    //     $data['status']      = StockTransaction::STATUS_PENDING;

    //     if ($data['type'] === StockTransaction::TYPE_OUT) {
    //         $product = Product::findOrFail($data['product_id']);
    //         if ($product->current_stock < $data['quantity']) {
    //             return back()->withErrors(['quantity' => 'Stok tidak mencukupi']);
    //         }
    //     }

    //     StockTransaction::create($data);

    //     return back()->with('success', 'Transaksi berhasil dibuat dan menunggu persetujuan.');
    // }
    public function store(StoreStockTransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status']  = StockTransaction::STATUS_PENDING;

        // Kalau Staff → hanya bikin request, belum ada assignment
        if (auth()->user()->role === 'Staff Gudang') {
            $data['assigned_by'] = null;
            $data['assigned_to'] = null;
        }

        // Kalau Manager → bisa assign tugas ke Staff
        elseif (auth()->user()->role === 'Manajer Gudang') {
            $data['assigned_by'] = Auth::id();
            $data['assigned_to'] = $request->assigned_to ?? null;
        }

        // Kalau Admin → fleksibel (jarang dipakai)
        elseif (auth()->user()->role === 'Admin') {
            $data['assigned_by'] = Auth::id();
            $data['assigned_to'] = $request->assigned_to ?? null;
        }

        // Validasi stok keluar
        if ($data['type'] === StockTransaction::TYPE_OUT) {
            $product = Product::findOrFail($data['product_id']);
            if ($product->current_stock < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Stok tidak mencukupi']);
            }
        }

        StockTransaction::create($data);

        return back()->with('success', 'Transaksi berhasil dibuat dan menunggu persetujuan.');
    }


    public function approveForm(StockTransaction $transaction)
    {
        if ($transaction->type !== StockTransaction::TYPE_IN) {
            abort(400, 'Hanya transaksi MASUK yang bisa di-approve');
        }

        $suppliers = Supplier::all();
        return view('transactions.approve', compact('transaction', 'suppliers'));
    }

    // public function approveIn(Request $request, StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     if ($transaction->type !== StockTransaction::TYPE_IN) {
    //         abort(400, 'Transaksi ini bukan transaksi masuk.');
    //     }

    //     $validated = $request->validate([
    //         'supplier_id' => ['required', 'exists:suppliers,id'],
    //     ]);

    //     $transaction->update([
    //         'status'      => StockTransaction::STATUS_APPROVED,
    //         'approved_by' => auth()->id(),
    //         'supplier_id' => $validated['supplier_id'],
    //     ]);

    //     $transaction->product()->increment('current_stock', $transaction->quantity);

    //     return redirect()->route('transactions.index')
    //         ->with('success', 'Transaksi Masuk disetujui, stok produk diperbarui.');
    // }

    // public function dispatchOut(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     if ($transaction->type !== StockTransaction::TYPE_OUT) {
    //         abort(400, 'Transaksi ini bukan transaksi keluar.');
    //     }

    //     $product = $transaction->product()->lockForUpdate()->first();
    //     if ($product->current_stock < $transaction->quantity) {
    //         return back()->withErrors(['quantity' => 'Stok tidak mencukupi saat eksekusi.']);
    //     }

    //     $transaction->update([
    //         'status'      => StockTransaction::STATUS_DISPATCHED,
    //         'approved_by' => Auth::id(),
    //     ]);

    //     $product->decrement('current_stock', $transaction->quantity);

    //     return back()->with('success', 'Transaksi Keluar berhasil dieksekusi.');
    // }
    public function approveIn(Request $request, StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->type !== StockTransaction::TYPE_IN) {
            abort(400, 'Transaksi ini bukan transaksi masuk.');
        }

        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
        ]);

        // Update hanya status + approved_by
        $transaction->update([
            'status'      => StockTransaction::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'supplier_id' => $validated['supplier_id'],
        ]);

        // 🚫 Tidak perlu increment stok → sudah otomatis via Observer

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi Masuk disetujui. Stok produk otomatis diperbarui.');
    }

    public function dispatchOut(StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->type !== StockTransaction::TYPE_OUT) {
            abort(400, 'Transaksi ini bukan transaksi keluar.');
        }

        $product = $transaction->product()->lockForUpdate()->first();

        if ($product->current_stock < $transaction->quantity) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi saat eksekusi.']);
        }

        // Update hanya status + approved_by
        $transaction->update([
            'status'      => StockTransaction::STATUS_DISPATCHED,
            'approved_by' => auth()->id(),
        ]);

        // 🚫 Tidak perlu decrement stok → sudah otomatis via Observer

        return back()->with('success', 'Transaksi Keluar berhasil dieksekusi.');
    }

    public function reject(StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->update([
            'status'      => StockTransaction::STATUS_REJECTED,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Transaksi ditolak.');
    }

    public function correctForm(StockTransaction $transaction)
    {
        if (!$transaction->is_correctable) {
            abort(403, 'Transaksi ini tidak bisa dikoreksi.');
        }

        $suppliers = Supplier::all();
        return view('transactions.correct', compact('transaction', 'suppliers'));
    }

    // public function correct(Request $request, StockTransaction $transaction)
    // {
    //     if (!$transaction->is_correctable) {
    //         abort(403, 'Transaksi ini tidak bisa dikoreksi.');
    //     }

    //     $data = $request->validate([
    //         'supplier_id' => ['required','exists:suppliers,id'],
    //         'notes'       => ['nullable','string'],
    //     ]);

    //     // keluarkan stok lama
    //     StockTransaction::create([
    //         'product_id'    => $transaction->product_id,
    //         'user_id'       => auth()->id(),
    //         'type'          => StockTransaction::TYPE_OUT,
    //         'quantity'      => $transaction->quantity,
    //         'date'          => now(),
    //         'status'        => StockTransaction::STATUS_DISPATCHED,
    //         'notes'         => 'Koreksi transaksi #'.$transaction->id,
    //         'correction_of' => $transaction->id,
    //     ]);

    //     // masukkan stok baru
    //     StockTransaction::create([
    //         'product_id'    => $transaction->product_id,
    //         'supplier_id'   => $data['supplier_id'],
    //         'user_id'       => auth()->id(),
    //         'type'          => StockTransaction::TYPE_IN,
    //         'quantity'      => $transaction->quantity,
    //         'date'          => now(),
    //         'status'        => StockTransaction::STATUS_APPROVED,
    //         'approved_by'   => auth()->id(),
    //         'notes'         => $data['notes'],
    //         'correction_of' => $transaction->id,
    //     ]);

    //     return redirect()->route('transactions.index')
    //         ->with('success','Transaksi dikoreksi dengan supplier baru.');
    // }
    public function correct(Request $request, StockTransaction $transaction)
    {
        // Cari transaksi terakhir dalam rantai koreksi
        $latest = StockTransaction::where('correction_of', $transaction->correction_of ?? $transaction->id)
            ->orWhere('id', $transaction->correction_of ?? $transaction->id)
            ->latest('id')
            ->first();

        if (
            $transaction->type !== StockTransaction::TYPE_IN ||
            $transaction->status !== StockTransaction::STATUS_APPROVED ||
            !$latest || $latest->id !== $transaction->id
        ) {
            abort(403, 'Transaksi ini tidak bisa dikoreksi.');
        }

        $data = $request->validate([
            'supplier_id' => ['required','exists:suppliers,id'],
            'notes'       => ['nullable','string'],
        ]);

        // 1) Buat transaksi keluar (untuk menarik stok lama)
        StockTransaction::create([
            'product_id'    => $transaction->product_id,
            'user_id'       => auth()->id(),
            'type'          => StockTransaction::TYPE_OUT,
            'quantity'      => $transaction->quantity,
            'date'          => now(),
            'status'        => StockTransaction::STATUS_DISPATCHED,
            'notes'         => 'Koreksi transaksi #'.$transaction->id,
            'correction_of' => $transaction->id,
            'approved_by'   => auth()->id(),
        ]);

        // 2) Buat transaksi masuk baru (dengan supplier benar)
        StockTransaction::create([
            'product_id'    => $transaction->product_id,
            'supplier_id'   => $data['supplier_id'],
            'user_id'       => auth()->id(),
            'type'          => StockTransaction::TYPE_IN,
            'quantity'      => $transaction->quantity,
            'date'          => now(),
            'status'        => StockTransaction::STATUS_APPROVED,
            'approved_by'   => auth()->id(),
            'notes'         => $data['notes'],
            'correction_of' => $transaction->id,
        ]);

        // 🚫 Tidak ada manual increment/decrement stok di sini
        // Semua akan dikerjakan otomatis oleh StockTransactionObserver

        return redirect()->route('transactions.index')
            ->with('success','Transaksi dikoreksi dengan supplier baru.');
    }

    // Staff buat request (sudah ada di store())
    // Manager validasi request Staff
    public function validateRequest(Request $request, StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->status !== StockTransaction::STATUS_PENDING) {
            return back()->withErrors(['msg' => 'Request ini sudah divalidasi sebelumnya.']);
        }

        $action = $request->input('action'); // approve | reject

        if ($action === 'approve') {
            $transaction->update([
                'status'      => StockTransaction::STATUS_APPROVED,
                'approved_by' => auth()->id(),
            ]);
            return back()->with('success', 'Request disetujui Manager.');
        } elseif ($action === 'reject') {
            $transaction->update([
                'status'      => StockTransaction::STATUS_REJECTED,
                'approved_by' => auth()->id(),
            ]);
            return back()->with('success', 'Request ditolak Manager.');
        }

        return back()->withErrors(['msg' => 'Aksi tidak valid.']);
    }

    // Manager meneruskan request ke Admin
    public function forwardToAdmin(StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->status !== StockTransaction::STATUS_APPROVED) {
            return back()->withErrors(['msg' => 'Request harus disetujui dulu sebelum dikirim ke Admin.']);
        }

        // Kirim notifikasi ke Admin (bisa pakai event/notification)
        // Sementara kita update field reference
        $transaction->update([
            'reference' => 'REQ-ADMIN-' . $transaction->id,
        ]);

        return back()->with('success', 'Request berhasil diteruskan ke Admin.');
    }

}