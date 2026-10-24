<x-admin-layout :title="$title">
    <h2 class="font-semibold text-xl text-gray-800 mb-4">{{ $title }}</h2>

    <div class="shadow px-6 py-4 bg-white rounded">
        {{-- enctype multipart wajib untuk upload file --}}
        <form enctype="multipart/form-data"
              action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST">
            @csrf
            @if (isset($product)) @method('PUT') @endif

            {{-- Ringkasan semua error validasi --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-md p-3">
                    <p class="font-semibold mb-1">Data belum bisa disimpan:</p>
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-6 gap-6 px-2 py-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="name" name="name" placeholder="Nama sepatu" required
                           value="{{ old('name', $product->name ?? '') }}"
                           class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md px-3 py-2">
                    @error('name') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="category_id" name="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->category_id }}"
                                @selected(old('category_id', $product->category_id ?? '') == $item->category_id)>{{ $item->category }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" id="price" name="price" required min="0"
                           value="{{ old('price', isset($product) ? (int) $product->price : '') }}"
                           class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md px-3 py-2">
                    @error('price') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" id="stock" name="stock" required min="0"
                           value="{{ old('stock', $product->stock ?? '') }}"
                           class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md px-3 py-2">
                    @error('stock') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="img" class="block text-sm font-medium text-gray-700">Foto (jpg/png, maks 1 MB)</label>
                    <input type="file" id="img" name="img" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                           class="mt-1 block w-full sm:text-sm border border-gray-300 rounded-md px-3 py-2">
                    @error('img') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                    @isset($product)
                        <img src="{{ $product->image_url }}" alt="" class="mt-2 h-16 rounded">
                    @endisset
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-300 rounded-md px-3 py-2">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description') <div class="text-xs text-red-800">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="px-4 py-3 text-right sm:px-6">
                <a href="{{ route('admin.products.index') }}" class="mr-3 text-sm text-gray-500 hover:underline">Batal</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</x-admin-layout>