<x-app-layout>
    <h1 class="text-lg font-bold mb-4">
        @if (auth()->user()->role === 'Admin')
            Buat Transaksi (Admin)
        @elseif (auth()->user()->role === 'Manajer Gudang')
            Buat Transaksi Masuk (Manager)
        @else
            Buat Transaksi (Staff)
        @endif
    </h1>

    @can('create', App\Models\StockTransaction::class)
        @if ($errors->any())
            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Produk --}}
            <div>
                <label for="product_id" class="block font-medium">Produk:</label>
                <select name="product_id" id="product_id" class="border rounded p-2 w-full" required>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} (stok: {{ $p->current_stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Supplier → hanya tampil untuk Admin & Manager --}}
            @if (auth()->user()->role !== 'Staff Gudang')
                <div>
                    <label for="supplier_id" class="block font-medium">Supplier:</label>
                    <select name="supplier_id" id="supplier_id" class="border rounded p-2 w-full">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Jenis Transaksi → kalau Staff boleh pilih, Manager & Admin default "Masuk" --}}
            @if (auth()->user()->role === 'Staff Gudang')
                <div>
                    <label for="type" class="block font-medium">Jenis Transaksi:</label>
                    <select name="type" id="type" class="border rounded p-2 w-full" required>
                        <option value="Masuk" {{ old('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="Keluar" {{ old('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
            @else
                <input type="hidden" name="type" value="Masuk">
            @endif

            {{-- Jumlah --}}
            <div>
                <label for="quantity" class="block font-medium">Jumlah:</label>
                <input type="number" name="quantity" id="quantity" min="1"
                       value="{{ old('quantity') }}"
                       class="border rounded p-2 w-full" required>
            </div>

            {{-- Tanggal --}}
            <div>
                <label for="date" class="block font-medium">Tanggal:</label>
                <input type="date" name="date" id="date"
                       value="{{ old('date', now()->toDateString()) }}"
                       class="border rounded p-2 w-full" required>
            </div>

            {{-- Catatan --}}
            <div>
                <label for="notes" class="block font-medium">Catatan:</label>
                <textarea name="notes" id="notes" class="border rounded p-2 w-full">{{ old('notes') }}</textarea>
            </div>

            {{-- Tombol --}}
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Buat Transaksi
            </button>
        </form>
    @else
        <p class="text-gray-500">Anda tidak punya izin membuat transaksi.</p>
    @endcan
</x-app-layout>
