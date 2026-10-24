<x-home-layout :title="$title">
    <h1 class="text-2xl font-extrabold text-slate-800 mb-4">Checkout</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 rounded-lg p-3">
            <p class="font-semibold mb-1">Data belum bisa disimpan:</p>
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6">
        {{-- FORM DATA PENERIMA --}}
        <form action="{{ route('checkout.store') }}" method="POST" class="md:col-span-2 bg-white p-5 rounded-xl border border-sky-100 space-y-4">
            @csrf
            <h2 class="font-bold text-slate-800">Data Penerima</h2>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-600 mb-1">Nama lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}"
                       class="w-full border border-sky-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300" required>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-600 mb-1">Nomor HP</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                       class="w-full border border-sky-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300" required>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-slate-600 mb-1">Alamat pengiriman</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full border border-sky-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300" required>{{ old('address') }}</textarea>
            </div>

            <div>
                <label for="note" class="block text-sm font-medium text-slate-600 mb-1">Catatan (opsional)</label>
                <input type="text" id="note" name="note" value="{{ old('note') }}"
                       class="w-full border border-sky-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300">
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('cart.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100">Kembali</a>
                <button type="submit" class="px-5 py-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white font-semibold">Buat Pesanan</button>
            </div>
        </form>

        {{-- RINGKASAN PESANAN --}}
        <aside class="bg-white p-5 rounded-xl border border-sky-100 h-fit">
            <h2 class="font-bold text-slate-800 mb-3">Ringkasan Pesanan</h2>
            <ul class="divide-y divide-sky-100">
                @foreach ($items as $item)
                    <li class="py-2 flex justify-between gap-3 text-sm">
                        <div>
                            <p class="font-medium text-slate-700">{{ $item['product']->name }}</p>
                            <p class="text-slate-500">Ukuran {{ $item['size'] }} × {{ $item['qty'] }}</p>
                        </div>
                        <p class="font-semibold text-slate-700 whitespace-nowrap">Rp {{ number_format($item['product']->price * $item['qty'], 0, ',', '.') }}</p>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 pt-3 border-t border-sky-100 flex justify-between items-center">
                <span class="text-slate-500">Total</span>
                <span class="text-xl font-extrabold text-sky-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </aside>
    </div>
</x-home-layout>
