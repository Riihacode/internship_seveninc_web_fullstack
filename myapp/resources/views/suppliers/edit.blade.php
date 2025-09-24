<x-app-layout>
    <h1>Edit Supplier</h1>

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama:</label><br>
        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required><br><br>

        <label>Alamat:</label><br>
        <textarea name="address">{{ old('address', $supplier->address) }}</textarea><br><br>

        <label>Telepon:</label><br>
        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $supplier->email) }}"><br><br>

        <button type="submit">Update</button>
    </form>
</x-app-layout>
