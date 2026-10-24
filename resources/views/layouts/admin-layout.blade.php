<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Admin SneakerLab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-sky-50 font-sans antialiased flex min-h-screen">
    <aside class="w-64 bg-white shadow-md hidden sm:block">
        <div class="p-4 font-extrabold text-xl text-sky-600">👟 SneakerLab Admin</div>
        <nav class="flex flex-col p-4 space-y-2 text-slate-600">
            <a href="{{ route('admin.products.index') }}"
               class="p-2 rounded hover:bg-sky-100 {{ request()->routeIs('admin.products.*') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                📦 Produk
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="p-2 rounded hover:bg-sky-100 {{ request()->routeIs('admin.orders.*') ? 'bg-sky-100 text-sky-700 font-semibold' : '' }}">
                🧾 Pesanan
            </a>
            <a href="{{ route('home') }}" class="p-2 rounded hover:bg-sky-100 text-sky-600 font-medium">← Ke Toko</a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <h1 class="font-bold text-sky-600 sm:hidden">SneakerLab Admin</h1>
            <span class="hidden sm:block text-slate-500">Halo, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-red-500 hover:underline">Logout</button>
            </form>
        </header>

        <main class="flex-1 p-6">
            @if (session('status'))
                <div class="mb-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg p-3">{{ session('status') }}</div>
            @endif

            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-sky-100 p-4 text-center text-sm text-slate-500">
            © {{ date('Y') }} SneakerLab Admin
        </footer>
    </div>
</body>
</html>
