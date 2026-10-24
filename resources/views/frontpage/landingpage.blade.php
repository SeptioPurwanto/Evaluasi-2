<x-home-layout :title="$title">
    <section class="bg-gradient-to-r from-sky-500 to-sky-300 text-white rounded-2xl p-8 mb-6 shadow">
        <h1 class="text-3xl font-extrabold mb-2">Temukan Sepatu Terbaikmu 👟</h1>
        <p class="text-sky-50">Running, Sport, Casual, dan Outdoor — {{ $products->total() }} model tersedia di katalog.</p>
    </section>

    {{-- Pencarian + filter kategori (method GET agar parameter tercatat di URL) --}}
    <form action="{{ route('home') }}" method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="s" value="{{ request('s') }}" placeholder="Cari sepatu..."
               class="flex-1 min-w-48 border border-sky-200 bg-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
        <select name="category" class="border border-sky-200 bg-white rounded-lg px-4 py-2">
            <option value="">Semua kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->category_id }}" @selected(request('category') == $cat->category_id)>{{ $cat->category }}</option>
            @endforeach
        </select>
        <button class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded-lg font-semibold">Cari</button>
    </form>

    @if ($products->isEmpty())
        <p class="text-center text-slate-500 py-16">Sepatu tidak ditemukan 😢</p>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($products as $shoe)
                @php $soldOut = $shoe->stock <= 0; @endphp
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition overflow-hidden border border-sky-100 flex flex-col">
                    <div class="relative">
                        <a href="{{ route('product.show', $shoe) }}">
                            <img src="{{ $shoe->image_url }}" alt="{{ $shoe->name }}" loading="lazy"
                                 class="w-full h-48 object-cover {{ $soldOut ? 'opacity-50 grayscale' : '' }}">
                        </a>
                        <span class="absolute top-2 left-2 bg-sky-500 text-white text-xs px-2 py-1 rounded-full">{{ $shoe->category->category }}</span>
                        @if ($soldOut)
                            <span class="absolute bottom-2 left-2 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">HABIS</span>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col gap-2 flex-1">
                        <a href="{{ route('product.show', $shoe) }}" class="font-bold text-slate-800 hover:text-sky-600">{{ $shoe->name }}</a>
                        <p class="text-sky-600 font-bold">{{ $shoe->price_rp }}</p>
                        @if (! $soldOut && $shoe->stock <= 3)
                            <p class="text-xs text-orange-500 font-semibold">Sisa {{ $shoe->stock }} pasang!</p>
                        @elseif (! $soldOut)
                            <p class="text-xs text-slate-400">Stok: {{ $shoe->stock }}</p>
                        @endif

                        <form action="{{ route('cart.add', $shoe) }}" method="POST" class="mt-auto flex flex-col gap-2">
                            @csrf
                            <select name="size" @disabled($soldOut) class="border border-sky-200 rounded-lg px-2 py-1 text-sm disabled:bg-slate-100">
                                @foreach ($sizes as $size)
                                    <option value="{{ $size }}" @selected($size === '41')>Ukuran {{ $size }}</option>
                                @endforeach
                            </select>
                            <button @disabled($soldOut)
                                    class="py-2 rounded-lg font-semibold text-white {{ $soldOut ? 'bg-slate-300 cursor-not-allowed' : 'bg-sky-500 hover:bg-sky-600' }}">
                                {{ $soldOut ? 'Stok Habis' : '+ Keranjang' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- appends/withQueryString membawa parameter pencarian ke halaman berikutnya --}}
        <div class="mt-6">{{ $products->links() }}</div>
    @endif
</x-home-layout>
