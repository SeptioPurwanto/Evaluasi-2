<x-home-layout :title="$title">
    <a href="{{ route('home') }}" class="text-sky-600 hover:underline">← Kembali</a>

    @php $soldOut = $product->stock <= 0; @endphp
    <div class="grid md:grid-cols-2 gap-8 mt-4 bg-white p-6 rounded-2xl shadow-sm border border-sky-100">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
             class="w-full rounded-xl object-cover max-h-96 {{ $soldOut ? 'grayscale opacity-60' : '' }}">

        <div class="flex flex-col gap-3">
            <span class="self-start bg-sky-100 text-sky-700 text-xs px-3 py-1 rounded-full">{{ $product->category->category }}</span>
            <h1 class="text-3xl font-extrabold text-slate-800">{{ $product->name }}</h1>
            <p class="text-2xl font-bold text-sky-600">{{ $product->price_rp }}</p>
            <p class="text-slate-600">{{ $product->description }}</p>

            @if ($soldOut)
                <p class="text-red-500 font-bold">Stok habis</p>
            @else
                <p class="{{ $product->stock <= 3 ? 'text-orange-500 font-semibold' : 'text-slate-500' }}">Stok tersedia: {{ $product->stock }} pasang</p>
            @endif

            <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-col gap-3">
                @csrf
                <select name="size" @disabled($soldOut) class="border border-sky-200 rounded-lg px-3 py-2 w-40">
                    @foreach ($sizes as $size)
                        <option value="{{ $size }}" @selected($size === '41')>Ukuran {{ $size }}</option>
                    @endforeach
                </select>
                <button @disabled($soldOut)
                        class="py-3 rounded-lg font-semibold text-white {{ $soldOut ? 'bg-slate-300 cursor-not-allowed' : 'bg-sky-500 hover:bg-sky-600' }}">
                    {{ $soldOut ? 'Stok Habis' : '+ Tambah ke Keranjang' }}
                </button>
            </form>
        </div>
    </div>
</x-home-layout>
