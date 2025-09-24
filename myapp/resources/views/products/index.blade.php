<x-app-layout>
    <h1>Daftar Produk</h1>

    {{-- Flash message sukses --}}
    @if (session('success'))
        <p style="color: green; margin-bottom:10px;">{{ session('success') }}</p>
    @endif

    {{-- Tombol tambah produk, hanya muncul jika user boleh create --}}
    @can('create', App\Models\Product::class)
        <p><a href="{{ route('products.create') }}">+ Tambah Produk</a></p>
    @endcan

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>SKU</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Min Stok</th>
                <th>Stok Saat Ini</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($products as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->sku }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category?->name }}</td>
                <td>{{ $p->supplier?->name }}</td>
                <td>{{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                <td>{{ number_format($p->selling_price, 0, ',', '.') }}</td>
                <td>{{ $p->minimum_stock }}</td>
                <td>{{ $p->current_stock }}</td>
                <td>
                    @can('update', $p)
                        <a href="{{ route('products.edit', $p->id) }}">Edit</a>
                    @endcan

                    @can('delete', $p)
                        <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus produk?')">Delete</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="10">Belum ada produk.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div style="margin-top:10px;">
        {{ $products->links() }}
    </div>
</x-app-layout>
