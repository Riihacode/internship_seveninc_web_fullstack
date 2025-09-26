<x-app-layout>
    <h1>Daftar User</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    {{-- Hanya Admin yang bisa tambah user --}}
    @can('create', App\Models\User::class)
        <p><a href="{{ route('admin.users.create') }}">+ Tambah User</a></p>
    @endcan

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $u)
                {{-- Jika user login = Manajer, tampilkan hanya Staff Gudang --}}
                @if(auth()->user()->role === 'Manajer Gudang' && $u->role !== 'Staff Gudang')
                    @continue
                @endif

                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role }}</td>
                    <td>
                        {{-- Edit & Delete hanya untuk Admin --}}
                        @can('update', $u)
                            <a href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
                        @endcan
                        @can('delete', $u)
                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus user?')">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada user.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:10px;">
        {{ $users->links() }}
    </div>
</x-app-layout>
