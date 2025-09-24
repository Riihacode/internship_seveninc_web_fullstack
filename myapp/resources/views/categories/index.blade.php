<x-app-layout>
    <h1>Daftar Kategori</h1>

    @if(session('success'))
        <p style="color: green; margin-bottom: 10px;">
            {{ session('success') }}</p>
    @endif

    <a href="{{ route('categories.create') }}">+ Tambah Kategori</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}">Edit</a>

                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus kategori?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 15px;">
        {{ $categories->links() }}
    </div>
</x-app-layout>
