<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Daftar Transaksi Stok</h1>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Form Search & Filter --}}
    <form method="GET" action="{{ route('transactions.index') }}" class="mb-4 flex space-x-2">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari produk/staff..."
               class="px-2 py-1 border rounded w-1/3">

        <select name="status" class="px-2 py-1 border rounded">
            <option value="">-- Semua Status --</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="Dikeluarkan" {{ request('status') == 'Dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
        </select>

        <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
            Filter
        </button>
    </form>
    
    <table class="w-full border border-gray-300 text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Produk</th>
                <th class="p-2 border">Supplier</th> {{-- ✅ Tambahan --}}
                <th class="p-2 border">Staff</th>
                <th class="p-2 border">Tipe</th>
                <th class="p-2 border">Jumlah</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Tanggal</th>
                <th class="p-2 border">Catatan</th>
                <th class="p-2 border">Disetujui Oleh</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $t)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">{{ $t->id }}</td>
                    <td class="p-2 border">{{ $t->product->name ?? '-' }}</td>
                    <td class="p-2 border">{{ $t->supplier->name ?? '-' }}</td> {{-- ✅ Tambahan --}}
                    <td class="p-2 border">{{ $t->user->name ?? '-' }}</td>
                    <td class="p-2 border">{{ $t->type }}</td>
                    <td class="p-2 border">{{ $t->quantity }}</td>
                    <td class="p-2 border">
                        @switch($t->status)
                            @case('Pending')
                                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Pending</span>
                                @break
                            @case('Diterima')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Diterima</span>
                                @break
                            @case('Ditolak')
                                <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Ditolak</span>
                                @break
                            @case('Dikeluarkan')
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Dikeluarkan</span>
                                @break
                            @default
                                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">{{ $t->status }}</span>
                        @endswitch
                    </td>
                    <td class="p-2 border">{{ $t->date->format('d-m-Y') }}</td>
                    <td class="p-2 border">{{ $t->notes }}</td>
                    <td class="p-2 border">{{ $t->approver->name ?? '-' }}</td>
                    <td class="p-2 border">
                        @if(in_array(auth()->user()->role, ['Admin','Manajer Gudang']))
                            {{-- @if($t->status === 'Pending' && $t->type === 'Masuk')
                                <form action="{{ route('transactions.approve_in', $t) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">
                                        Approve Masuk
                                    </button>
                                </form>
                            @endif --}}
                            @if($t->status === 'Pending' && $t->type === 'Masuk')
                                <a href="{{ route('transactions.approve_form', $t) }}"
                                class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600">
                                Approve Masuk
                                </a>
                            @endif

                            @if($t->status === 'Pending' && $t->type === 'Keluar')
                                <form action="{{ route('transactions.dispatch_out', $t) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                                        Dispatch Keluar
                                    </button>
                                </form>
                            @endif

                            @if($t->status === 'Pending')
                                <form action="{{ route('transactions.reject', $t) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                        Tolak
                                    </button>
                                </form>
                            @endif

                            {{-- 🔹 Tambahan tombol Koreksi hanya jika sudah Diterima --}}
                            {{-- @if($t->status === 'Diterima')
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-orange-500 text-white text-xs rounded hover:bg-orange-600">
                                Koreksi
                                </a>
                            @endif --}}
                            {{-- ✅ Tombol koreksi jika sudah Approved --}}
                            {{-- @if($t->status === 'Diterima' && $t->type === 'Masuk')
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}
                            {{-- ⬇️ Tombol koreksi hanya bila memenuhi semua syarat --}}
                            {{-- @if($t->is_correctable)
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                    class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                    Koreksi Supplier
                                </a>
                            @endif --}}
                            {{-- @if($t->type === 'Masuk' && $t->status === 'Diterima')
                                @if(is_null($t->correction_of) && !$t->corrections()->exists())
                                    <a href="{{ route('transactions.correct_form', $t) }}"
                                    class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                    Koreksi Supplier
                                    </a>
                                @else
                                    <button disabled
                                            class="px-2 py-1 bg-gray-300 text-gray-600 text-xs rounded cursor-not-allowed"
                                            title="Transaksi sudah pernah dikoreksi">
                                        Koreksi Supplier
                                    </button>
                                @endif
                            @endif --}}
                            {{-- @if(in_array(auth()->user()->role, ['Admin','Manajer Gudang']))
                                @if($t->is_correctable)
                                    <a href="{{ route('transactions.correct_form', $t) }}"
                                    class="px-2 py-1 bg-orange-500 text-white text-xs rounded hover:bg-orange-600">
                                    Koreksi
                                    </a>
                                @endif
                            @endif --}}
                            {{-- @if(
                                    $t->type === 'Masuk' &&
                                    $t->status === 'Diterima' &&
                                    $t->id === \App\Models\StockTransaction::where('correction_of', $t->correction_of ?? $t->id)
                                        ->orWhere('id', $t->correction_of ?? $t->id)
                                        ->latest('id')
                                        ->value('id')
                                )
                                <a href="{{ route('transactions.correct_form', $t) }}" class="...">Koreksi</a>
                            @endif --}}
                            {{-- @if(
                                $t->type === 'Masuk' &&
                                $t->status === 'Diterima' &&
                                $t->id === \App\Models\StockTransaction::where(function($q) use ($t) {
                                        $rootId = $t->correction_of ?? $t->id;
                                        $q->where('correction_of', $rootId)
                                        ->orWhere('id', $rootId);
                                    })
                                    ->latest('id')
                                    ->value('id')
                            )
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}
                            {{-- @if(
                                $t->type === 'Masuk' &&
                                $t->status === 'Diterima' &&
                                $t->id === \App\Models\StockTransaction::where(function($q) use ($t) {
                                        // ambil akar rantai: kalau dia hasil koreksi, pakai correction_of pertama
                                        $rootId = $t->correction_of ?? $t->id;
                                        $q->where('correction_of', $rootId)
                                        ->orWhere('id', $rootId);
                                    })
                                    ->latest('id') // cari yang terakhir di rantai
                                    ->value('id')
                            )
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}

                            {{-- @php
                                $rootId = $t->correction_of ?? $t->id;
                                $latestIn = \App\Models\StockTransaction::where(function($q) use ($rootId) {
                                        $q->where('id', $rootId)->orWhere('correction_of', $rootId);
                                    })
                                    ->where('type', \App\Models\StockTransaction::TYPE_IN)
                                    ->orderByDesc('id')
                                    ->first();
                            @endphp

                            @if(
                                $t->type === \App\Models\StockTransaction::TYPE_IN &&
                                $t->status === \App\Models\StockTransaction::STATUS_APPROVED &&
                                $latestIn && $t->id === $latestIn->id
                            )
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}

                            {{-- @if($t->can_correct)
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}

                            {{-- @if($t->can_correct && in_array(auth()->user()->role, ['Admin','Manajer Gudang']))
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}
                            {{-- @if($t->can_correct && in_array(auth()->user()->role, ['Admin','Manajer Gudang']))
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif --}}
                            @if($t->can_correct && in_array(auth()->user()->role, ['Admin','Manajer Gudang']))
                                <a href="{{ route('transactions.correct_form', $t) }}"
                                class="px-2 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">
                                Koreksi Supplier
                                </a>
                            @endif

                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="p-4 text-center text-gray-500">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</x-app-layout>
