<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Manager Dashboard
        </h2>

        <li><a href="{{ route('transactions.index') }}">Kelola Transaksi Stok</a></li>
        <li><a href="{{ route('categories.index') }}">Kelola Categories</a></li>
        <li><a href="{{ route('suppliers.index') }}">Kelola Suppliers</a></li>
        <li><a href="{{ route('products.index') }}">Kelola Products</a></li>
        <li><a href="{{ route('admin.users.index') }}">Kelola Users</a></li>
        <li><a href="{{ route('transactions.index') }}">Kelola Transaksi Stok</a></li>
        <li><a href="{{ route('attributes.index') }}">Kelola Product Attribute</a></li>
    </x-slot>
    <div class="p-6">Halo Manager</div>
</x-app-layout>