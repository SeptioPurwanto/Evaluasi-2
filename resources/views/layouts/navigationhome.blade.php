@php
    $cartQty = collect(session('cart', []))->sum('qty');
    $link = fn ($active) => $active ? 'font-bold border-b-2 border-white pb-1' : 'hover:text-sky-100';
@endphp

<nav class="bg-sky-500 text-white px-6 md:px-8 py-4 flex flex-wrap gap-3 justify-between items-center shadow-md">
    <a href="{{ route('home') }}" class="font-extrabold text-2xl tracking-wide">
        👟 Sneaker<span class="text-sky-100">Lab</span>
    </a>

    <div class="flex gap-6 items-center">
        <a href="{{ route('home') }}" class="{{ $link(request()->routeIs('home', 'product.show')) }}">Beranda</a>
        <a href="{{ route('cart.index') }}" class="{{ $link(request()->routeIs('cart.*')) }}">
            <span class="relative inline-block">
                🛒 Keranjang
                @if ($cartQty > 0)
                    <span class="absolute -top-3 -right-5 bg-white text-sky-600 text-xs px-2 py-0.5 rounded-full font-bold">{{ $cartQty }}</span>
                @endif
            </span>
        </a>
        <a href="{{ route('about') }}" class="{{ $link(request()->routeIs('about')) }}">Tentang</a>

        @auth
            <a href="{{ route('admin.products.index') }}" class="{{ $link(false) }}">Admin</a>
        @else
            <a href="{{ route('login') }}" class="{{ $link(false) }}">Login</a>
        @endauth
    </div>
</nav>
