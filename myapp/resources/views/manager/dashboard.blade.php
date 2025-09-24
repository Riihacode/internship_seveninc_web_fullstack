<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Manager Dashboard
        </h2>

        <li><a href="{{ route('transactions.index') }}">Kelola Transaksi Stok</a></li>
    </x-slot>
    <div class="p-6">Halo Manager</div>
</x-app-layout>