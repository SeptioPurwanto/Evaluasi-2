<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Landing page: katalog + pencarian + filter kategori + pagination
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('s'), fn ($q) => $q->where('name', 'like', '%' . $request->s . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->orderBy('product_id')
            ->paginate(8)
            ->withQueryString();

        return view('frontpage.landingpage', [
            'title' => 'Beranda',
            'products' => $products,
            'categories' => Category::all(),
            'sizes' => Product::SIZES,
        ]);
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('frontpage.detail', [
            'title' => $product->name,
            'product' => $product,
            'sizes' => Product::SIZES,
        ]);
    }

    public function about()
    {
        return view('frontpage.about', ['title' => 'Tentang']);
    }
}
