<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Validasi Transaksi Masuk</h1>

    <div class="mb-4 p-2 border rounded bg-gray-50">
        <p><strong>Produk:</strong> {{ $transaction->product->name }}</p>
        <p><strong>Jumlah:</strong> {{ $transaction->quantity }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->date->format('d-m-Y') }}</p>
        {{-- <p><strong>Catatan Staff:</strong> {{ $transaction->notes }}</p> --}}
        <p>
            <strong>Catatan {{ $transaction->user->role }}:</strong> 
            {{ $transaction->notes ?? '-' }}
        </p>
    </div>

    @can('update', $transaction)
        <form method="POST" action="{{ route('transactions.approve_in', $transaction) }}" class="space-y-4">
            @csrf

            {{-- Supplier --}}
            @if($transaction->supplier)
                <div>
                    <p><strong>Supplier:</strong> {{ $transaction->supplier->name }}</p>
                    {{-- supaya tetap terkirim ke controller --}}
                    <input type="hidden" name="supplier_id" value="{{ $transaction->supplier_id }}">
                </div>
            @else
                <div>
                    <label for="supplier_id" class="block font-medium">Pilih Supplier:</label>
                    <select name="supplier_id" id="supplier_id" class="border rounded p-2 w-full" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Approve & Simpan
            </button>
        </form>
    @else
        <p class="text-gray-500">Anda tidak punya izin untuk approve transaksi ini.</p>
    @endcan
</x-app-layout>
