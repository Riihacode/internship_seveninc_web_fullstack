<x-app-layout>
    <h1>Detail Kategori</h1>

    <p><strong>ID:</strong> {{ $category->id }}</p>
    <p><strong>Nama:</strong> {{ $category->name }}</p>
    <p><strong>Deskripsi:</strong> {{ $category->description }}</p>

    <a href="{{ route('categories.index') }}">← Kembali</a>
</x-app-layout>
