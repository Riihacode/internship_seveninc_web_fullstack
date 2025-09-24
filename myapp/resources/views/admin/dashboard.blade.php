<x-app-layout>
    <h1>Admin Dashboard</h1>
    <p>Halo Admin 👋</p>

    <nav>
        <ul>
            <li><a href="{{ route('categories.index') }}">Kelola Categories</a></li>
            <li><a href="{{ route('suppliers.index') }}">Kelola Suppliers</a></li>
            <li><a href="{{ route('products.index') }}">Kelola Products</a></li>
            <li><a href="{{ route('admin.users.index') }}">Kelola Users</a></li>
            <li><a href="{{ route('transactions.index') }}">Kelola Transaksi Stok</a></li>
            <li><a href="{{ route('attributes.index') }}">Kelola Product Attribute</a></li>
        </ul>
    </nav>
</x-app-layout>
