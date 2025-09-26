<x-app-layout>
    <h1>Tambah Produk</h1>

    @if ($errors->any())
        <div style="color:red; margin-bottom:10px;">
            <ul style="margin-left:16px;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <label>Nama:</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required><br><br>

        <label>SKU:</label><br>
        <input type="text" name="sku" value="{{ old('sku') }}" required><br><br>

        <label>Kategori:</label><br>
        <select name="category_id" required>
            <option value="">-- pilih --</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
            @endforeach
        </select><br><br>

        <label>Supplier:</label><br>
        <select name="supplier_id" required>
            <option value="">-- pilih --</option>
            @foreach ($suppliers as $s)
                <option value="{{ $s->id }}" @selected(old('supplier_id')==$s->id)>{{ $s->name }}</option>
            @endforeach
        </select><br><br>

        <label>Harga Beli:</label><br>
        <input type="number" name="purchase_price" step="0.01" min="0" value="{{ old('purchase_price') }}" required><br><br>

        <label>Harga Jual:</label><br>
        <input type="number" name="selling_price" step="0.01" min="0" value="{{ old('selling_price') }}" required><br><br>

        <label>Minimum Stok:</label><br>
        <input type="number" name="minimum_stock" min="0" value="{{ old('minimum_stock', 0) }}" required><br><br>

        <label>Stok Saat Ini:</label><br>
        <input type="number" name="current_stock" min="0" value="{{ old('current_stock', 0) }}" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="description">{{ old('description') }}</textarea><br><br>

        <label>Path Gambar (opsional):</label><br>
        <input type="text" name="image" value="{{ old('image') }}"><br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('products.index') }}">← Kembali ke daftar produk</a>
</x-app-layout>
