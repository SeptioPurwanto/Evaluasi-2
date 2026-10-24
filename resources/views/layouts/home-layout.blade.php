<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | {{ config('app.name', 'SneakerLab') }}</title>
    {{-- Tailwind lewat CDN, sama seperti modul --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col bg-sky-50">
        @include('layouts.navigationhome')

        <main class="flex-1 max-w-6xl w-full mx-auto p-6">
            @if (session('status'))
                <div class="mb-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg p-3">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-50 text-red-600 border border-red-200 rounded-lg p-3">{{ session('error') }}</div>
            @endif

            {{-- Slot: konten berubah sesuai view yang dipanggil --}}
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-sky-100 text-center text-sm text-slate-500 p-4">
            © {{ date('Y') }} SneakerLab | Laravel + MySQL — Web Programming Undiksha
        </footer>
    </div>
</body>
</html>
