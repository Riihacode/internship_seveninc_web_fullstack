<x-app-layout>
    <x-slot name="header">
        Staff Dashboard
    </x-slot>
    <div class="p-6">
        Halo Staff 👋
    </div>
    <p>Halo, {{ auth()->user()->name }} 👋</p>

    {{-- ✅ Form Buat Transaksi Baru --}}
    <h2>Buat Transaksi Baru</h2>
    @if ($errors->any())
        <div style="color:red; margin-bottom:10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div style="color:green; margin-bottom:10px;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <label for="product_id">Produk:</label>
        <select name="product_id" required>
            @foreach(\App\Models\Product::all() as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (stok: {{ $product->current_stock }})</option>
            @endforeach
        </select><br><br>

        <label for="type">Tipe:</label>
        <select name="type" required>
            <option value="Masuk">Masuk</option>
            <option value="Keluar">Keluar</option>
        </select><br><br>

        <label for="quantity">Jumlah:</label>
        <input type="number" name="quantity" min="1" required><br><br>

        <label for="date">Tanggal:</label>
        <input type="date" name="date" required><br><br>

        <label for="notes">Catatan:</label>
        <textarea name="notes"></textarea><br><br>

        <button type="submit">Simpan</button>
    </form>

    <hr>

    {{-- ✅ Riwayat Transaksi Staff --}}
    <h2>Riwayat Transaksi Saya</h2>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; text-align:left;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse(\App\Models\StockTransaction::with(['product','approver'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at','desc')
                ->limit(10)
                ->get() as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->product->name }}</td>
                    <td>{{ $t->type }}</td>
                    <td>{{ $t->quantity }}</td>
                    <td>{{ $t->status }}</td>
                    <td>{{ $t->date->format('d-m-Y') }}</td>
                    <td>{{ $t->notes }}</td>
                    <td>{{ $t->approver?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>