<x-app-layout>
    <h1>Edit Kategori</h1>

    @can('update', $category)
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nama:</label><br>
            <input type="text" name="name" value="{{ old('name', $category->name) }}">
            @error('name')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
            <br>

            <label>Deskripsi:</label><br>
            <textarea name="description">{{ old('description', $category->description) }}</textarea><br><br>

            <button type="submit">Update</button>
        </form>
    @else
        <p style="color: gray;">Anda tidak memiliki izin untuk mengedit kategori ini.</p>
    @endcan

    <br>
    <a href="{{ route('categories.index') }}">← Kembali ke daftar kategori</a>
</x-app-layout>
