<x-app-layout>
    <h1>Daftar Supplier</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('suppliers.create') }}">+ Tambah Supplier</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus supplier?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada supplier.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-app-layout>
