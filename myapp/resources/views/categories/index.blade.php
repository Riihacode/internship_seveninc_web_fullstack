<x-app-layout>
    <h1>Daftar Kategori</h1>

    {{-- Flash message sukses --}}
    @if(session('success'))
        <p style="color: green; margin-bottom:10px;">{{ session('success') }}</p>
    @endif

    {{-- Hanya Admin boleh create --}}
    @can('create', App\Models\Category::class)
        <p>
            <a href="{{ route('categories.create') }}">+ Tambah Kategori</a>
        </p>
    @endcan

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
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
                    <td>
                        <a href="{{ route('categories.show', $category->id) }}">
                            {{ $category->name }}
                        </a>
                    </td>
                    <td>{{ $category->description }}</td>
                    <td>
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
                                <button type="submit" onclick="return confirm('Yakin hapus kategori?')">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center">Belum ada kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        {{ $categories->links() }}
    </div>
</x-app-layout>
