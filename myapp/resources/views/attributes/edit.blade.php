<x-app-layout>
    <h1>Edit Atribut Produk</h1>

    <form action="{{ route('attributes.update', $attribute->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Produk</label>
        <select name="product_id" required>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ $p->id == $attribute->product_id ? 'selected' : '' }}>
                    {{ $p->name }}
                </option>
            @endforeach
        </select>
        <br><br>

        <label>Nama Atribut</label>
        <input type="text" name="name" value="{{ old('name', $attribute->name) }}" required>
        <br><br>

        <label>Value (opsional)</label>
        <input type="text" name="value" value="{{ old('value', $attribute->value) }}">
        <br><br>1

        <button type="submit">Simpan Perubahan</button>
    </form>
</x-app-layout>
