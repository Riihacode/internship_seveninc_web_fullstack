<x-app-layout>
    <h1>Tambah Atribut Produk</h1>

    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('attributes.store') }}" method="POST">
        @csrf

        <label for="product_id">Produk</label>
        <select name="product_id" id="product_id" required>
            <option value="">-- Pilih Produk --</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <br><br>

        <label for="name">Nama Atribut</label>
        <input type="text" name="name" id="name" required>
        <br><br>

        <label for="value">Nilai Atribut</label>
        <input type="text" name="value" id="value">
        <small>Boleh dikosongkan</small>
        <br><br>

        <button type="submit">Simpan</button>
    </form>
</x-app-layout>