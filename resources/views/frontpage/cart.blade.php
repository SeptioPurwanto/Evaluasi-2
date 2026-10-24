<x-home-layout :title="$title">
    @if ($items->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl border border-sky-100">
            <p class="text-5xl mb-3">🛒</p>
            <p class="text-slate-500 mb-4">Keranjang masih kosong.</p>
            <a href="{{ route('home') }}" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded-lg">Mulai Belanja</a>
        </div>
    @else
        <h1 class="text-2xl font-extrabold text-slate-800 mb-4">Keranjang Belanja</h1>

        <div class="space-y-3">
            @foreach ($items as $item)
                <div class="bg-white p-4 rounded-xl border border-sky-100 flex gap-4 items-center flex-wrap">
                    <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="w-24 h-20 object-cover rounded-lg">
                    <div class="flex-1 min-w-40">
                        <p class="font-bold text-slate-800">{{ $item['product']->name }}</p>
                        <p class="text-sm text-slate-500">Ukuran {{ $item['size'] }} • {{ $item['product']->price_rp }}</p>
                        <p class="text-xs text-slate-400">Stok tersedia: {{ $item['product']->stock }}</p>
                    </div>

                    <form action="{{ route('cart.update', $item['key']) }}" method="POST" class="flex items-center gap-2">
                        @csrf @method('PATCH')
                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" max="{{ $item['product']->stock }}"
                               class="w-16 border border-sky-200 rounded px-2 py-1 text-center">
                        <button class="text-sm border border-sky-200 rounded px-2 py-1 hover:bg-sky-100">Ubah</button>
                    </form>

                    <p class="w-32 text-right font-bold text-sky-600">Rp {{ number_format($item['product']->price * $item['qty'], 0, ',', '.') }}</p>

                    <form action="{{ route('cart.remove', $item['key']) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline text-sm">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 bg-white p-5 rounded-xl border border-sky-100 flex flex-wrap gap-4 justify-between items-center">
            <div>
                <p class="text-slate-500">{{ $items->sum('qty') }} pasang</p>
                <p class="text-2xl font-extrabold text-sky-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Kosongkan</button>
                </form>
                <a href="{{ route('checkout.create') }}" class="px-5 py-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white font-semibold">Checkout</a>
            </div>
        </div>
    @endif
</x-home-layout>
