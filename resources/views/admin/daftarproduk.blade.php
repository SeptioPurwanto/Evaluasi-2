<x-admin-layout :title="$title">
    <h2 class="font-semibold text-xl text-gray-800 mb-4">{{ $title }}</h2>

    <div class="shadow px-6 py-4 bg-white rounded">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <a href="{{ route('admin.products.create') }}">
                    <button class="px-4 py-1 text-sm rounded text-purple-600 font-semibold border border-purple-200 hover:text-white hover:bg-purple-600">Tambah</button>
                </a>
                @if (request()->routeIs('admin.categories.products'))
                    <a href="{{ route('admin.products.index') }}" class="ml-2 text-sm text-gray-600 hover:underline">&larr; Semua produk</a>
                @endif
            </div>

            {{-- PENCARIAN + FILTER (method GET) --}}
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex gap-2 md:justify-end">
                <select name="category" class="border border-gray-300 rounded-md text-sm px-2 py-1">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->category_id }}" @selected(request()->query('category', $category->category_id ?? null) == $cat->category_id)>{{ $cat->category }}</option>
                    @endforeach
                </select>
                <input type="text" name="s" value="{{ request('s') }}" placeholder="Cari nama..." class="px-4 py-1 border border-gray-300 rounded-md text-sm">
                <button type="submit" class="rounded-md border px-4 py-1 border-gray-300 bg-gray-50 text-blue-600 hover:text-white hover:bg-blue-600">Cari</button>
            </form>
        </div>

        <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($products as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-10 w-14 rounded object-cover" src="{{ $item->image_url }}" alt="">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->price_rp }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $item->stock <= 3 ? 'text-red-500 font-bold' : 'text-gray-900' }}">{{ $item->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{-- Relasi belongsTo: Product -> Category. Klik untuk melihat produk per kategori (hasMany) --}}
                                <a href="{{ route('admin.categories.products', $item->category) }}" class="text-blue-600 hover:underline">{{ $item->category->category }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.products.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('admin.products.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <button class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Anda yakin menghapus {{ addslashes($item->name) }}?')" type="submit">Del</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (membawa parameter pencarian lewat withQueryString di controller) --}}
        <div class="m-4">{{ $products->links() }}</div>
    </div>
</x-admin-layout>