<x-app-layout>
    <h1 class="text-lg font-bold mb-4">Buat Transaksi (Admin)</h1>

    @can('create', App\Models\StockTransaction::class)
        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Produk --}}
            <div>
                <label for="product_id" class="block font-medium">Produk:</label>
                <select name="product_id" id="product_id" class="border rounded p-2 w-full" required>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (stok: {{ $p->current_stock }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Supplier --}}
            <div>
                <label for="supplier_id" class="block font-medium">Supplier:</label>
                <select name="supplier_id" id="supplier_id" class="border rounded p-2 w-full">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach ($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah --}}
            <div>
                <label for="quantity" class="block font-medium">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" class="border rounded p-2 w-full" min="1" required>
            </div>

            {{-- Tanggal --}}
            <div>
                <label for="date" class="block font-medium">Tanggal:</label>
                <input type="date" name="date" id="date" class="border rounded p-2 w-full" value="{{ now()->toDateString() }}" required>
            </div>

            {{-- Catatan --}}
            <div>
                <label for="notes" class="block font-medium">Catatan:</label>
                <textarea name="notes" id="notes" class="border rounded p-2 w-full"></textarea>
            </div>

            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Buat Transaksi</button>
        </form>
    @endcan
</x-app-layout>
