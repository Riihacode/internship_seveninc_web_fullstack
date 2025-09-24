<x-app-layout>
    <h1>Edit User</h1>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama:</label><br>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required><br><br>

        <label>Password (biarkan kosong jika tidak ingin mengubah):</label><br>
        <input type="password" name="password"><br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="Admin" @selected($user->role == 'Admin')>Admin</option>
            <option value="Manajer Gudang" @selected($user->role == 'Manajer Gudang')>Manajer Gudang</option>
            <option value="Staff Gudang" @selected($user->role == 'Staff Gudang')>Staff Gudang</option>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</x-app-layout>
