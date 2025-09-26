<x-app-layout>
    <h1 class="text-lg font-bold mb-4">Buat Transaksi (Staff)</h1>

    @can('create', App\Models\StockTransaction::class)
        @if ($errors->any())
            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="product_id" class="block font-medium">Produk:</label>
                <select name="product_id" id="product_id" class="border rounded p-2 w-full" required>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (stok: {{ $p->current_stock }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block font-medium">Jenis Transaksi:</label>
                <select name="type" id="type" class="border rounded p-2 w-full" required>
                    <option value="Masuk">Masuk</option>
                    <option value="Keluar">Keluar</option>
                </select>
            </div>

            <div>
                <label for="quantity" class="block font-medium">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" min="1" class="border rounded p-2 w-full" required>
            </div>

            <div>
                <label for="date" class="block font-medium">Tanggal:</label>
                <input type="date" name="date" id="date" value="{{ now()->toDateString() }}" class="border rounded p-2 w-full" required>
            </div>

            <div>
                <label for="notes" class="block font-medium">Catatan:</label>
                <textarea name="notes" id="notes" class="border rounded p-2 w-full"></textarea>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Buat Transaksi
            </button>
        </form>
    @else
        <p class="text-gray-500">Anda tidak punya izin membuat transaksi.</p>
    @endcan
</x-app-layout>
