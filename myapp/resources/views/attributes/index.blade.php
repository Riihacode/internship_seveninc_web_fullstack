<x-app-layout>
    <h1>Daftar Atribut Produk</h1>

    {{-- Tombol tambah hanya untuk Admin --}}
    @can('create', App\Models\ProductAttribute::class)
        <a href="{{ route('attributes.create') }}">+ Tambah Atribut</a>
    @endcan

    @if(session('success'))
        <div style="color:green; margin-top:10px;">{{ session('success') }}</div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:10px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Nama Atribut</th>
                <th>Value</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attributes as $attr)
                <tr>
                    <td>{{ $attr->id }}</td>
                    <td>{{ $attr->product->name }}</td>
                    <td>{{ $attr->name }}</td>
                    <td>{{ $attr->value ?? '-' }}</td>
                    <td>
                        {{-- Tombol Edit hanya untuk Admin --}}
                        @can('update', $attr)
                            <a href="{{ route('attributes.edit', $attr->id) }}">Edit</a>
                        @endcan

                        {{-- Tombol Hapus hanya untuk Admin --}}
                        @can('delete', $attr)
                            <form action="{{ route('attributes.destroy', $attr->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus atribut ini?')">Hapus</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada atribut</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div style="margin-top:15px;">
        {{ $attributes->links() }}
    </div>
</x-app-layout>
