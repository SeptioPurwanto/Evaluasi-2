<x-home-layout :title="$title">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-sky-100 space-y-5">
        <div class="text-center">
            <p class="text-5xl mb-2">🎉</p>
            <h1 class="text-2xl font-extrabold text-slate-800">Pesanan berhasil dibuat!</h1>
            <p class="text-slate-500">Terima kasih, {{ $order->name }}. Pesananmu sudah kami terima.</p>
        </div>

        <div class="bg-sky-50 rounded-xl p-4 text-sm text-slate-600 space-y-1">
            <p><span class="font-semibold text-slate-700">Kode pesanan:</span> {{ $order->code }}</p>
            <p><span class="font-semibold text-slate-700">Nomor HP:</span> {{ $order->phone }}</p>
            <p><span class="font-semibold text-slate-700">Alamat:</span> {{ $order->address }}</p>
            @if ($order->note)
                <p><span class="font-semibold text-slate-700">Catatan:</span> {{ $order->note }}</p>
            @endif
        </div>

        <ul class="divide-y divide-sky-100">
            @foreach ($order->items as $item)
                <li class="py-2 flex justify-between gap-3 text-sm">
                    <div>
                        <p class="font-medium text-slate-700">{{ $item->product_name }}</p>
                        <p class="text-slate-500">Ukuran {{ $item->size }} × {{ $item->qty }} @ {{ $item->price_rp }}</p>
                    </div>
                    <p class="font-semibold text-slate-700 whitespace-nowrap">{{ $item->subtotal_rp }}</p>
                </li>
            @endforeach
        </ul>

        <div class="flex justify-between items-center border-t border-sky-100 pt-3">
            <span class="text-slate-500">Total</span>
            <span class="text-2xl font-extrabold text-sky-600">{{ $order->total_rp }}</span>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-block bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded-lg">Lanjut Belanja</a>
        </div>
    </div>
</x-home-layout>
