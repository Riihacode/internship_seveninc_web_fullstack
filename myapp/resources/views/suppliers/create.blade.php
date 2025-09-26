<x-app-layout>
    <h1>Tambah Supplier</h1>

    @can('create', App\Models\Supplier::class)
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <label>Nama:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Alamat:</label><br>
            <textarea name="address"></textarea><br><br>

            <label>Telepon:</label><br>
            <input type="text" name="phone"><br><br>

            <label>Email:</label><br>
            <input type="email" name="email"><br><br>

            <button type="submit">Simpan</button>
        </form>
    @else
        <p style="color:red;">Anda tidak memiliki izin untuk menambah supplier.</p>
    @endcan
</x-app-layout>
