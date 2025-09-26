<x-app-layout>
    <h1>Detail Kategori</h1>

    <p><strong>ID:</strong> {{ $category->id }}</p>
    <p><strong>Nama:</strong> {{ $category->name }}</p>
    <p><strong>Deskripsi:</strong> {{ $category->description }}</p>

    <div style="margin-top: 10px;">
        {{-- Hanya Admin & Manajer boleh edit --}}
        @can('update', $category)
            <a href="{{ route('categories.edit', $category->id) }}">Edit</a>
        @endcan

        {{-- Hanya Admin boleh delete --}}
        @can('delete', $category)
            <form action="{{ route('categories.destroy', $category->id) }}" 
                  method="POST" 
                  style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin hapus kategori ini?')">
                    Delete
                </button>
            </form>
        @endcan
    </div>

    <br>
    <a href="{{ route('categories.index') }}">← Kembali ke daftar kategori</a>
</x-app-layout>
