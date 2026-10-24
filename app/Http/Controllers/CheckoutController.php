<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

// Checkout: keranjang (session) -> tabel orders + order_items (MySQL)
class CheckoutController extends Controller
{
    // Ubah isi session keranjang menjadi daftar item lengkap dengan data produk
    private function cartItems()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('product_id', collect($cart)->pluck('product_id'))->get()->keyBy('product_id');

        return collect($cart)->map(function ($row) use ($products) {
            $product = $products->get($row['product_id']);

            return $product ? ['product' => $product, 'size' => $row['size'], 'qty' => $row['qty']] : null;
        })->filter()->values();
    }

    public function create()
    {
        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        return view('frontpage.checkout', [
            'title' => 'Checkout',
            'items' => $items,
            'total' => $items->sum(fn ($i) => $i['product']->price * $i['qty']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => ['required', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'address' => 'required|string|min:10',
            'note' => 'nullable|string|max:255',
        ], [
            'required' => 'Kolom :attribute harus diisi.',
            'phone.regex' => 'Nomor HP tidak valid (8-20 karakter, hanya angka, +, - dan spasi).',
            'address.min' => 'Alamat minimal 10 karakter.',
        ]);

        $items = $this->cartItems();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        try {
            $order = DB::transaction(function () use ($data, $items) {
                // Total pembelian per produk (satu produk bisa punya beberapa ukuran)
                $need = $items->groupBy(fn ($i) => $i['product']->product_id)
                    ->map(fn ($group) => $group->sum('qty'));

                // Kunci baris produk agar stok tidak berubah saat diproses
                $locked = Product::whereIn('product_id', $need->keys())->lockForUpdate()->get()->keyBy('product_id');

                foreach ($need as $productId => $qty) {
                    $product = $locked->get($productId);
                    if (! $product || $product->stock < $qty) {
                        throw new RuntimeException('Stok "' . ($product->name ?? 'produk') . '" tidak mencukupi.');
                    }
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'note' => $data['note'] ?? null,
                    'total' => $items->sum(fn ($i) => $i['product']->price * $i['qty']),
                    'status' => 'baru',
                ]);

                foreach ($items as $item) {
                    $order->items()->create([
                        'product_id' => $item['product']->product_id,
                        'product_name' => $item['product']->name,
                        'size' => $item['size'],
                        'price' => $item['product']->price,
                        'qty' => $item['qty'],
                        'subtotal' => $item['product']->price * $item['qty'],
                    ]);
                }

                // Kurangi stok
                foreach ($need as $productId => $qty) {
                    $locked->get($productId)->decrement('stock', $qty);
                }

                return $order;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        session()->forget('cart');
        session(['last_order' => $order->order_id]);

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        // Hanya pemilik pesanan yang baru dibuat (satu sesi) yang boleh melihat halaman ini
        if (session('last_order') !== $order->order_id) {
            return redirect()->route('home');
        }

        return view('frontpage.checkout-success', [
            'title' => 'Pesanan Berhasil',
            'order' => $order->load('items'),
        ]);
    }
}
