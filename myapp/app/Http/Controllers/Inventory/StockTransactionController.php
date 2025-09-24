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

// class StockTransactionController extends Controller
// {
//     //
// }

class StockTransactionController extends Controller
{
    // public function store(Request $r)
    // {
    //     $data = $r->validate([
    //         'product_id'    => ['required', 'exist:product, id'],
    //         'type'          => ['required', 'in:Masuk', 'Keluar'],
    //         'quantity'      => ['required', 'integer', 'min:1'],
    //         'date'          => ['required', 'date'],
    //         'notes'         => ['nullable', 'string'],
    //         'reference'     => ['nullable', 'string', 'max:64'],
    //         'unit_cost'     => ['nullable', 'numeric'],
    //     ]);

    //     $data['user_id'] = Auth::id();
    //     $data['status'] = StockTransaction::STATUS_PENDING;

    //     // Untuk transaksi keluar, pastikan stok cukup (cek cepat di product)
    //     if ($data['type'] === StockTransaction::TYPE_OUT) {
    //         $p = Product::findOfFail($data['product_id']);
    //         if ($p->cuurent_stock < $data['quantity']) {
    //             return back()->withErrors(['quantity' => 'Stok tidak mencukupi']);
    //         }
    //     }

    //     StockTransaction::create($data);
    //     return back()->with('status', 'Transaksi dibuat (Pending).');
    // }

    // public function store(StoreStockTransactionRequest $request)
    // {
    //     $data = $request->validated();    
    //     $data['user_id']    = auth()->id();
    //     $data['status']     = StockTransaction::STATUS_PENDING;

    //     StockTransaction::create($data);

    //     return back()->with('status', 'Transaksi berhasil dibuat (Pending).');
    // }

    // public function update(UpdateStockTransactionRequest $request, StockTransaction $transaction) {
    //     $transaction->update([
    //         'status'        => $request->status,
    //         'approved_by'   => auth()->id(),
    //         'notes'         => $request->notes,
    //     ]);

    //     return back()->with('status', 'Transaksi berhasil diperbarui.');
    // }

    // public function approveIn(StockTransaction $transaction) 
    // {
    //     $this->authorize('approve', $transaction);
    //     if ($transaction->type !== StockTransaction::TYPE_OUT) abort(400);

    //     // cek stok latest sebelum eksekusi
    //     $p = $transaction->product()->lockForUpdate()->first(); // aman di MySQL
    //     if ($p->current_stock < $transaction->quantity) {
    //         return back()->withErrors(['quantity' => 'Stok tidak mencukupi saat eksekusi.']);
    //     }

    //     $transaction->update([
    //         'status'        => StockTransaction::STATUS_DISPATCHED,
    //         'approved_by'   => Auth::id(),
    //     ]);

    //     return back()->with('status', 'Transaksi Keluar dieksekusi.');
    // }

    // public function reject(StockTransaction $transaction) 
    // {
    //     $this->authorize('approve', $transaction);
    //     $transaction->update([
    //         'status'        => StockTransaction::STATUS_REJECTED,
    //         'approved_by'   => Auth::id(),
    //     ]);

    //     return back()->with('status', 'Transaksi ditolak.');
    // }


    // =====================================================================
    // public function index(Request $request)
    // {
    //     // Semua transaksi + relasi produk & user (biar efisien)
    //     // $transactions = \App\Models\StockTransaction::with(['product', 'user', 'approver'])
    //     //     ->orderBy('created_at', 'desc')
    //     //     ->paginate(10);

    //     // return view('transactions.index', compact('transactions'));


    //     // $query = StockTransaction::with(['product', 'user', 'approver'])
    //     //     ->orderBy('created_at', 'desc');

    //     // // filter berdasarkan status
    //     // if ($request->filled('status')) {
    //     //     $q = $request->q;
    //     //     $query->whereHas('product', fn($qBuilder) => 
    //     //         $qBuilder->where('name', 'like', "%$q%")
    //     //     )->orWhereHas('user', fn($qBuilder) => 
    //     //         $qBuilder->where('name', 'like', "%$q%")
    //     //     );
    //     // }

    //     // $transactions = $query->paginate(10)->withQueryString();

    //     // return view('transactions.index', compact('transactions'));
    //     $query = StockTransaction::with(['product', 'supplier', 'user', 'approver'])
    //         ->orderBy('created_at', 'desc');

    //     // 🔎 Filter pencarian (produk / staff)
    //     if ($request->filled('q')) {
    //         $q = $request->q;
    //         $query->where(function ($sub) use ($q) {
    //             $sub->whereHas('product', fn($p) => $p->where('name', 'like', "%$q%"))
    //                 ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$q%"));
    //         });
    //     }

    //     // 🔎 Filter status
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     $transactions = $query->paginate(10)->withQueryString();

    //     return view('transactions.index', compact('transactions'));
    // }

    // Form create
    // public function create() 
    // {
    //     $products = Product::all();
    //     return view('transactions.create_staff', compact('products'));
    // }
    // public function index(Request $request)
    // {
    //     $query = StockTransaction::with(['product','user','approver','supplier'])
    //         ->withCount('corrections')   // ⬅️ supaya bisa cek sudah ada koreksi
    //         ->orderBy('created_at','desc');

    //     if ($request->filled('q')) {
    //         $q = $request->q;
    //         $query->whereHas('product', fn($qb)=>$qb->where('name','like',"%$q%"))
    //             ->orWhereHas('user', fn($qb)=>$qb->where('name','like',"%$q%"));
    //     }
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     $transactions = $query->paginate(10)->withQueryString();
    //     return view('transactions.index', compact('transactions'));
    // }
    // public function index(Request $request)
    // {
    //     $transactions = StockTransaction::with(['product','supplier','user','approver'])
    //         ->when($request->q, fn($q,$v) =>
    //             $q->whereHas('product', fn($p) => $p->where('name','like',"%$v%"))
    //             ->orWhereHas('user', fn($u) => $u->where('name','like',"%$v%"))
    //         )
    //         ->when($request->status, fn($q,$v) => $q->where('status',$v))
    //         ->orderByDesc('id')
    //         ->paginate(10);

    //     // 🔹 Tandai transaksi mana yang boleh dikoreksi
    //     // foreach ($transactions as $t) {
    //     //     $rootId = $t->correction_of ?? $t->id;

    //     //     $latestIn = StockTransaction::where(function($q) use ($rootId) {
    //     //             $q->where('id', $rootId)->orWhere('correction_of', $rootId);
    //     //         })
    //     //         ->where('type', StockTransaction::TYPE_IN)
    //     //         ->orderByDesc('id')
    //     //         ->first();

    //     //     $t->can_correct = $latestIn && $t->id === $latestIn->id && $t->status === StockTransaction::STATUS_APPROVED;
    //     // }
    //     // foreach ($transactions as $t) {
    //     //     $rootId = $t->correction_of ?? $t->id;

    //     //     $latest = StockTransaction::where(function($q) use ($rootId) {
    //     //             $q->where('id', $rootId)
    //     //             ->orWhere('correction_of', $rootId);
    //     //         })
    //     //         ->orderByDesc('id')
    //     //         ->first();

    //     //     $t->can_correct =
    //     //         $latest &&
    //     //         $t->id === $latest->id &&                 // hanya ID terakhir
    //     //         $t->type === StockTransaction::TYPE_IN && // hanya transaksi Masuk
    //     //         $t->status === StockTransaction::STATUS_APPROVED;
    //     // }
    //     // foreach ($transactions as $t) {
    //     //     // Cari "akar" transaksi
    //     //     $rootId = $t->correction_of ?? $t->id;

    //     //     // Cari transaksi terakhir dalam rantai
    //     //     $latest = StockTransaction::where(function($q) use ($rootId) {
    //     //             $q->where('id', $rootId)
    //     //             ->orWhere('correction_of', $rootId);
    //     //         })
    //     //         ->orderByDesc('id')
    //     //         ->first();

    //     //     // Hanya ID terakhir yang bisa dikoreksi
    //     //     $t->can_correct = $latest 
    //     //         && $t->id === $latest->id
    //     //         && $t->type === StockTransaction::TYPE_IN
    //     //         && $t->status === StockTransaction::STATUS_APPROVED;
    //     // }

    //     foreach ($transactions as $t) {
    //         // Cari root dari rantai
    //         $rootId = $t->correction_of ?? $t->id;

    //         // Cari transaksi terakhir dalam rantai ini
    //         $latest = StockTransaction::where(function($q) use ($rootId) {
    //                 $q->where('id', $rootId)
    //                 ->orWhere('correction_of', $rootId);
    //             })
    //             ->orderByDesc('id')
    //             ->first();

    //         // Pastikan hanya transaksi "Masuk" terbaru yang boleh dikoreksi
    //         $t->can_correct = (
    //             $latest &&
    //             $t->id === $latest->id &&                 // hanya ID paling akhir
    //             $t->type === StockTransaction::TYPE_IN && // hanya transaksi Masuk
    //             $t->status === StockTransaction::STATUS_APPROVED
    //         );
    //     }



    //     return view('transactions.index', compact('transactions'));
    // }

    // public function index(Request $request)
    // {
    //     $transactions = StockTransaction::with(['product','supplier','user','approver'])
    //         ->when($request->q, fn($q,$v) =>
    //             $q->whereHas('product', fn($p) => $p->where('name','like',"%$v%"))
    //             ->orWhereHas('user', fn($u) => $u->where('name','like',"%$v%"))
    //         )
    //         ->when($request->status, fn($q,$v) => $q->where('status',$v))
    //         ->orderByDesc('id')
    //         ->paginate(10);

    //     foreach ($transactions as $t) {
    //         // Cari rootId (transaksi asli)
    //         $rootId = $t->correction_of ?? $t->id;

    //         // Cari transaksi paling akhir dalam rantai dengan cara rekursif/loop
    //         $latest = $rootId;
    //         while (true) {
    //             $next = StockTransaction::where('correction_of', $latest)
    //                 ->orderByDesc('id')
    //                 ->first();

    //             if (!$next) {
    //                 break; // tidak ada koreksi lebih lanjut
    //             }

    //             $latest = $next->id;
    //         }

    //         // Ambil transaksi object dari ID terakhir
    //         $latestTransaction = StockTransaction::find($latest);

    //         // Tandai hanya transaksi terakhir "Masuk" yang boleh dikoreksi
    //         $t->can_correct = (
    //             $latestTransaction &&
    //             $t->id === $latestTransaction->id &&
    //             $t->type === StockTransaction::TYPE_IN &&
    //             $t->status === StockTransaction::STATUS_APPROVED
    //         );
    //     }


    //     return view('transactions.index', compact('transactions'));
    // }
    // public function index(Request $request)
    // {
    //     $transactions = StockTransaction::with(['product','supplier','user','approver'])
    //         ->when($request->q, fn($q,$v) =>
    //             $q->whereHas('product', fn($p) => $p->where('name','like',"%$v%"))
    //             ->orWhereHas('user', fn($u) => $u->where('name','like',"%$v%"))
    //         )
    //         ->when($request->status, fn($q,$v) => $q->where('status',$v))
    //         ->orderByDesc('id')
    //         ->paginate(10);

    //     // 🔹 Map root → latest id
    //     $latestMap = [];
    //     foreach (StockTransaction::all() as $trx) {
    //         $rootId = $trx->correction_of ?? $trx->id;

    //         if (!isset($latestMap[$rootId]) || $trx->id > $latestMap[$rootId]) {
    //             $latestMap[$rootId] = $trx->id;
    //         }
    //     }

    //     // 🔹 Tandai hanya transaksi terakhir yang bisa dikoreksi
    //     foreach ($transactions as $t) {
    //         $rootId = $t->correction_of ?? $t->id;
    //         $latestId = $latestMap[$rootId] ?? $t->id;

    //         $t->can_correct = (
    //             $t->id === $latestId &&
    //             $t->type === StockTransaction::TYPE_IN &&
    //             $t->status === StockTransaction::STATUS_APPROVED
    //         );
    //     }

    //     return view('transactions.index', compact('transactions'));
    // }
    private function getRootId(StockTransaction $trx)
    {
        $current = $trx;
        while ($current->correction_of) {
            $current = StockTransaction::find($current->correction_of);
        }
        return $current->id;
    }


    // public function index(Request $request)
    // {
    //     $transactions = StockTransaction::with(['product','supplier','user','approver'])
    //         ->when($request->q, fn($q,$v) =>
    //             $q->whereHas('product', fn($p) => $p->where('name','like',"%$v%"))
    //             ->orWhereHas('user', fn($u) => $u->where('name','like',"%$v%"))
    //         )
    //         ->when($request->status, fn($q,$v) => $q->where('status',$v))
    //         ->orderByDesc('id')
    //         ->paginate(10);

    //     // ambil latest id untuk setiap rantai
    //     $latestMap = StockTransaction::selectRaw('MAX(id) as latest_id, COALESCE(correction_of, id) as root_id')
    //         ->groupBy('root_id')
    //         ->pluck('latest_id','root_id');

    //     // foreach ($transactions as $t) {
    //     //     $rootId = $t->correction_of ?? $t->id;
    //     //     $latestId = $latestMap[$rootId] ?? $t->id;

    //     //     $t->can_correct = (
    //     //         $t->id === $latestId &&
    //     //         $t->type === StockTransaction::TYPE_IN &&
    //     //         $t->status === StockTransaction::STATUS_APPROVED
    //     //     );
    //     // }
    //     foreach ($transactions as $t) {
    //     // cari root pertama (yang correction_of null)
    //     $rootId = $this->getRootId($t);

    //     // cari transaksi terakhir dalam rantai root ini
    //     $latestId = StockTransaction::where(function($q) use ($rootId) {
    //                 $q->where('id', $rootId)->orWhere('correction_of', $rootId);
    //             })
    //             ->max('id');

    //         $t->can_correct = (
    //             $t->id === $latestId &&
    //             $t->type === StockTransaction::TYPE_IN &&
    //             $t->status === StockTransaction::STATUS_APPROVED
    //         );
    //     }


    //     return view('transactions.index', compact('transactions'));
    // }

    public function index(Request $request)
    {
        $transactions = StockTransaction::with(['product','supplier','user','approver'])
            ->when($request->q, fn($q,$v) =>
                $q->whereHas('product', fn($p) => $p->where('name','like',"%$v%"))
                ->orWhereHas('user', fn($u) => $u->where('name','like',"%$v%"))
            )
            ->when($request->status, fn($q,$v) => $q->where('status',$v))
            ->orderByDesc('id')
            ->paginate(10);

        // cari root asli (correction_of = null) → latest id per rantai
        $latestMap = [];
        foreach (StockTransaction::all() as $trx) {
            $root = $trx;
            while ($root->correction_of) {
                $root = StockTransaction::find($root->correction_of);
            }
            $rootId = $root->id;

            if (!isset($latestMap[$rootId]) || $trx->id > $latestMap[$rootId]) {
                $latestMap[$rootId] = $trx->id;
            }
        }

        // tandai transaksi yang boleh dikoreksi
        foreach ($transactions as $t) {
            // temukan root asli transaksi ini
            $root = $t;
            while ($root->correction_of) {
                $root = StockTransaction::find($root->correction_of);
            }
            $rootId = $root->id;

            $latestId = $latestMap[$rootId] ?? $t->id;

            $t->can_correct = (
                $t->id === $latestId &&
                $t->type === StockTransaction::TYPE_IN &&
                $t->status === StockTransaction::STATUS_APPROVED
            );
        }

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::all();

        if (auth()->user()->role === 'Manajer Gudang') {
            $suppliers = Supplier::all();
            return view('transactions.create_manager', compact('products', 'suppliers'));
        }

        // default Staff
        return view('transactions.create_staff', compact('products'));
    }

    /**
     * Staff/Admin/Manager membuat transaksi baru.
     * Status awal = Pending
     */
    public function store(StoreStockTransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status']  = StockTransaction::STATUS_PENDING;

        // Cek stok hanya untuk transaksi keluar
        if ($data['type'] === StockTransaction::TYPE_OUT) {
            $product = Product::findOrFail($data['product_id']);
            if ($product->current_stock < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Stok tidak mencukupi']);
            }
        }

        StockTransaction::create($data);

        return back()->with('success', 'Transaksi berhasil dibuat dan menunggu persetujuan.');
    }

    /**
     * Manager/Admin menyetujui transaksi MASUK.
     * Stok produk akan ditambah.
     */
    // public function approveIn(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     if ($transaction->type !== StockTransaction::TYPE_IN) {
    //         abort(400, 'Transaksi ini bukan transaksi masuk.');
    //     }

    //     $transaction->update([
    //         'status'      => StockTransaction::STATUS_APPROVED,
    //         'approved_by' => Auth::id(),
    //     ]);

    //     // Update stok produk
    //     $transaction->product()->increment('current_stock', $transaction->quantity);

    //     return back()->with('success', 'Transaksi Masuk disetujui, stok produk diperbarui.');
    // }
    public function approveForm(StockTransaction $transaction)
    {
        if ($transaction->type !== StockTransaction::TYPE_IN) {
            abort(400, 'Hanya transaksi MASUK yang bisa di-approve');
        }

        $suppliers = Supplier::all();
        return view('transactions.approve', compact('transaction', 'suppliers'));
    }

    public function approveIn(Request $request, StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->type !== StockTransaction::TYPE_IN) {
            abort(400, 'Transaksi ini bukan transaksi masuk.');
        }

        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
        ]);

        $transaction->update([
            'status'      => StockTransaction::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'supplier_id' => $validated['supplier_id'],
        ]);

        // Update stok produk
        $transaction->product()->increment('current_stock', $transaction->quantity);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi Masuk disetujui dengan supplier, stok produk diperbarui.');
    }

    /**
     * Manager/Admin mengeksekusi transaksi KELUAR.
     * Stok produk akan dikurangi.
     */
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

        $transaction->update([
            'status'      => StockTransaction::STATUS_DISPATCHED,
            'approved_by' => Auth::id(),
        ]);

        // Update stok produk
        $product->decrement('current_stock', $transaction->quantity);

        return back()->with('success', 'Transaksi Keluar berhasil dieksekusi.');
    }

    /**
     * Manager/Admin menolak transaksi.
     */
    public function reject(StockTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->update([
            'status'      => StockTransaction::STATUS_REJECTED,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Transaksi ditolak.');
    }

    // Koreksi salah input oleh manger agar tidak merusak audit pertanggung jawaban
    // public function correct(Request $request, StockTransaction $transaction)
    // {
    //     // Validasi supplier baru
    //     $data = $request->validate([
    //         'supplier_id' => 'required|exists:suppliers,id',
    //         'notes'       => 'nullable|string',
    //     ]);

    //     // 1. Buat transaksi keluar untuk membatalkan
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

    //     // 2. Buat transaksi masuk baru dengan supplier benar
    //     StockTransaction::create([
    //         'product_id'    => $transaction->product_id,
    //         'supplier_id'   => $data['supplier_id'],
    //         'user_id'       => auth()->id(),
    //         'type'          => StockTransaction::TYPE_IN,
    //         'quantity'      => $transaction->quantity,
    //         'date'          => now(),
    //         'status'        => StockTransaction::STATUS_APPROVED,
    //         'notes'         => $data['notes'],
    //         'correction_of' => $transaction->id,
    //     ]);

    //     // return back()->with('success', 'Transaksi dikoreksi dengan supplier baru.');
    //     return redirect()->route('transactions.index')
    //             ->with('success', 'Transaksi dikoreksi dengan supplier baru.');
    // }


    // public function correctForm(StockTransaction $transaction)
    // {
    //     abort_if(!$transaction->is_correctable, 403, 'Transaksi ini tidak bisa dikoreksi.');

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }
    // public function correctForm(StockTransaction $transaction)
    // {
    //     // Inline guard
    //     $latest = StockTransaction::where('correction_of', $transaction->correction_of ?? $transaction->id)
    //         ->orWhere('id', $transaction->correction_of ?? $transaction->id)
    //         ->latest('id')
    //         ->first();

    //     if (
    //         $transaction->type !== StockTransaction::TYPE_IN ||
    //         $transaction->status !== StockTransaction::STATUS_APPROVED ||
    //         !$latest || $latest->id !== $transaction->id
    //     ) {
    //         abort(403, 'Transaksi ini tidak bisa dikoreksi.');
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }

    public function correctForm(StockTransaction $transaction)
    {
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

        $suppliers = \App\Models\Supplier::all();
        return view('transactions.correct', compact('transaction', 'suppliers'));
    }


    // public function correct(Request $request, StockTransaction $transaction)
    // {
    //     abort_if(!$transaction->is_correctable, 403, 'Transaksi ini tidak bisa dikoreksi.');

    //     $data = $request->validate([
    //         'supplier_id' => ['required','exists:suppliers,id'],
    //         'notes'       => ['nullable','string'],
    //     ]);

    //     // 1) keluarkan stok lama
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

    //     // 2) masukkan stok baru dengan supplier benar
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
        // Guard optional: biar extra aman
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

        // 1) keluarkan stok lama
        StockTransaction::create([
            'product_id'    => $transaction->product_id,
            'user_id'       => auth()->id(),
            'type'          => StockTransaction::TYPE_OUT,
            'quantity'      => $transaction->quantity,
            'date'          => now(),
            'status'        => StockTransaction::STATUS_DISPATCHED,
            'notes'         => 'Koreksi transaksi #'.$transaction->id,
            'correction_of' => $transaction->id,
        ]);

        // 2) masukkan stok baru dengan supplier benar
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

        return redirect()->route('transactions.index')
            ->with('success','Transaksi dikoreksi dengan supplier baru.');
    }


    // public function correct(Request $request, StockTransaction $transaction)
    // {
    //     abort_if(!$transaction->is_correctable, 403, 'Transaksi ini tidak bisa dikoreksi.');

    //     $data = $request->validate([
    //         'supplier_id' => ['required','exists:suppliers,id'],
    //         'notes'       => ['nullable','string'],
    //     ]);

    //     // 1) keluarkan stok (koreksi keluar)
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

    //     // 2) masukkan stok kembali dengan supplier benar
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

    // public function correctForm(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     // Hanya boleh koreksi untuk transaksi MASUK yang sudah Approved
    //     if ($transaction->type !== StockTransaction::TYPE_IN ||
    //         $transaction->status !== StockTransaction::STATUS_APPROVED) {
    //         abort(400, 'Hanya transaksi MASUK yang sudah disetujui yang bisa dikoreksi.');
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }
    // // public function correctForm(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     // Guard ketat: hanya transaksi MASUK + sudah Approved + belum pernah dikoreksi
    //     if ($transaction->type !== StockTransaction::TYPE_IN
    //         || $transaction->status !== StockTransaction::STATUS_APPROVED
    //         || !is_null($transaction->correction_of)
    //         || $transaction->corrections()->exists()) 
    //     {
    //         abort(400, 'Transaksi ini tidak bisa dikoreksi.');
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }
    // public function correctForm(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     // Guard: hanya transaksi MASUK yang Approved dan BELUM dikoreksi
    //     // if ($transaction->type !== StockTransaction::TYPE_IN
    //     //     || $transaction->status !== StockTransaction::STATUS_APPROVED
    //     //     || $transaction->corrections()->exists())   // cukup cek ini saja
    //     // {
    //     //     abort(403, 'Transaksi ini tidak bisa dikoreksi.');
    //     // }
    //     // if ($transaction->type !== StockTransaction::TYPE_IN
    //     //     || $transaction->status !== StockTransaction::STATUS_APPROVED
    //     //     || $transaction->correction_of !== null) {
    //     //     abort(403, 'Transaksi ini tidak bisa dikoreksi.');
    //     // }

    //     if ($transaction->type !== StockTransaction::TYPE_IN
    //         || $transaction->status !== StockTransaction::STATUS_APPROVED
    //         || !is_null($transaction->correction_of)
    //         || $transaction->corrections()->exists()) 
    //     {
    //         abort(400, 'Transaksi ini tidak bisa dikoreksi.');
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }
    // public function correctForm(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     // Hanya boleh koreksi jika: Masuk + sudah Approved + belum pernah dikoreksi
    //     if ($transaction->type !== StockTransaction::TYPE_IN
    //         || $transaction->status !== StockTransaction::STATUS_APPROVED
    //         || $transaction->corrections()->exists()) 
    //     {
    //         abort(403, 'Transaksi ini tidak bisa dikoreksi.');
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }
    // public function correctForm(StockTransaction $transaction)
    // {
    //     $this->authorize('update', $transaction);

    //     // Guard ketat
    //     if ($transaction->type !== StockTransaction::TYPE_IN) {
    //         abort(403, 'Hanya transaksi MASUK yang bisa dikoreksi.');
    //     }
    //     if ($transaction->status !== StockTransaction::STATUS_APPROVED) {
    //         abort(403, 'Hanya transaksi yang sudah DITERIMA yang bisa dikoreksi.');
    //     }
    //     if ($transaction->corrections()->exists()) {
    //         abort(403, 'Transaksi ini sudah pernah dikoreksi.');
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }

    // public function correctForm(StockTransaction $transaction)
    // {
    //     // $this->authorize('update', $transaction);

    //     if ($transaction->type !== StockTransaction::TYPE_IN) {
    //         dd("Debug: type salah → {$transaction->type}");
    //     }
    //     if ($transaction->status !== StockTransaction::STATUS_APPROVED) {
    //         dd("Debug: status salah → {$transaction->status}");
    //     }
    //     if ($transaction->corrections()->exists()) {
    //         dd("Debug: sudah ada koreksi");
    //     }

    //     $suppliers = \App\Models\Supplier::all();
    //     return view('transactions.correct', compact('transaction', 'suppliers'));
    // }
}
