<x-app-layout>
    <h1>Tambah User</h1>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <label>Nama:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Manajer Gudang">Manajer Gudang</option>
            <option value="Staff Gudang">Staff Gudang</option>
        </select><br><br>

        <button type="submit">Simpan</button>
    </form>
</x-app-layout>
