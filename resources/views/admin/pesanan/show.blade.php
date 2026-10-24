<x-admin-layout :title="$title">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold text-xl text-gray-800">{{ $title }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Semua pesanan</a>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 shadow px-6 py-4 bg-white rounded">
            <h3 class="font-semibold text-gray-800 mb-3">Item Pesanan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $item->product_name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->size }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->price_rp }}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">{{ $item->qty }}</td>
                                <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ $item->subtotal_rp }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                            <td class="px-4 py-3 text-right font-bold text-sky-600">{{ $order->total_rp }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="shadow px-6 py-4 bg-white rounded text-sm text-gray-700 space-y-1">
                <h3 class="font-semibold text-gray-800 mb-2">Data Pembeli</h3>
                <p><span class="font-medium">Nama:</span> {{ $order->name }}</p>
                <p><span class="font-medium">HP:</span> {{ $order->phone }}</p>
                <p><span class="font-medium">Alamat:</span> {{ $order->address }}</p>
                @if ($order->note)
                    <p><span class="font-medium">Catatan:</span> {{ $order->note }}</p>
                @endif
                <p><span class="font-medium">Dibuat:</span> {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="shadow px-6 py-4 bg-white rounded space-y-3">
                @csrf
                @method('PATCH')
                <h3 class="font-semibold text-gray-800">Status Pesanan</h3>
                <select name="status" class="w-full border border-gray-300 rounded-md text-sm px-2 py-2">
                    @foreach (\App\Models\Order::STATUSES as $status)
                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="w-full rounded-md bg-sky-500 hover:bg-sky-600 text-white py-2 text-sm font-semibold">Simpan Status</button>
            </form>
        </div>
    </div>
</x-admin-layout>
