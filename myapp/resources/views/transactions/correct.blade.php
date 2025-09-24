<x-app-layout>
    <h1 class="text-lg font-bold mb-4">
        Koreksi Supplier — Transaksi #{{ $transaction->id }}
    </h1>

    <div class="mb-4 p-3 border rounded bg-gray-50 text-sm">
        <div><b>Produk:</b> {{ $transaction->product->name }}</div>
        <div><b>Qty:</b> {{ $transaction->quantity }}</div>
        <div><b>Tanggal:</b> {{ $transaction->date->format('d-m-Y') }}</div>
        <div><b>Supplier saat ini:</b> {{ $transaction->supplier->name ?? '-' }}</div>
        <div><b>Status:</b> {{ $transaction->status }}</div>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('transactions.correct', $transaction) }}" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">Supplier baru</label>
            <select name="supplier_id" class="border rounded p-2 w-full" required>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Catatan (opsional)</label>
            <textarea name="notes" class="border rounded p-2 w-full"></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                Simpan Koreksi
            </button>
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 border rounded">
                Batal
            </a>
        </div>
    </form>
</x-app-layout>
