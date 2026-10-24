<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

// Keranjang disimpan di session (belum perlu tabel orders)
class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = Product::with('category')->whereIn('product_id', collect($cart)->pluck('product_id'))->get()->keyBy('product_id');

        $items = collect($cart)->map(function ($row, $key) use ($products) {
            $product = $products->get($row['product_id']);
            return $product ? ['key' => $key, 'product' => $product, 'size' => $row['size'], 'qty' => $row['qty']] : null;
        })->filter()->values();

        $total = $items->sum(fn ($i) => $i['product']->price * $i['qty']);

        return view('frontpage.cart', ['title' => 'Keranjang', 'items' => $items, 'total' => $total]);
    }

    public function add(Request $request, Product $product)
    {
        $data = $request->validate(['size' => 'required|in:' . implode(',', Product::SIZES)]);

        $cart = session('cart', []);
        $inCart = collect($cart)->where('product_id', $product->product_id)->sum('qty');

        if ($product->stock <= 0 || $inCart >= $product->stock) {
            return back()->with('error', "Stok \"{$product->name}\" tidak mencukupi.");
        }

        $key = $product->product_id . '-' . $data['size'];
        $cart[$key] = [
            'product_id' => $product->product_id,
            'size' => $data['size'],
            'qty' => ($cart[$key]['qty'] ?? 0) + 1,
        ];
        session(['cart' => $cart]);

        return back()->with('status', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, string $key)
    {
        $cart = session('cart', []);
        if (! isset($cart[$key])) {
            return back();
        }

        $qty = (int) $request->input('qty', 1);
        $product = Product::find($cart[$key]['product_id']);
        $others = collect($cart)->where('product_id', $cart[$key]['product_id'])->except($key)->sum('qty');
        $qty = min($qty, max(0, ($product->stock ?? 0) - $others));

        if ($qty <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = $qty;
        }
        session(['cart' => $cart]);

        return back();
    }

    public function remove(string $key)
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return back()->with('status', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('status', 'Keranjang dikosongkan.');
    }
}
