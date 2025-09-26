<x-app-layout>
    <h1>Tambah Kategori</h1>

    {{-- Validasi error --}}
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('create', App\Models\Category::class)
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <label>Nama:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Deskripsi:</label><br>
            <textarea name="description"></textarea><br><br>

            <button type="submit">Simpan</button>
        </form>
    @else
        <p style="color: gray;">Anda tidak memiliki izin untuk menambah kategori.</p>
    @endcan

    <br>
    <a href="{{ route('categories.index') }}">← Kembali ke daftar kategori </a>
</x-app-layout>
