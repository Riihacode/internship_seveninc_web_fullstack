<x-app-layout>
    <h1>Daftar User</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <p><a href="{{ route('admin.users.create') }}">+ Tambah User</a></p>

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
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->role }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus user?')">Delete</button>
                        </form>
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
