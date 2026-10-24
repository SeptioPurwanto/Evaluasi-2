<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function rules(?Product $product = null): array
    {
        return [
            'name' => 'required|string|max:150|unique:products,name' . ($product ? ',' . $product->product_id . ',product_id' : ''),
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ];
    }

    private function messages(): array
    {
        return [
            'required' => 'Kolom :attribute harus diisi.',
            'numeric' => 'Kolom :attribute harus angka.',
            'integer' => 'Kolom :attribute harus bilangan bulat.',
            'unique' => ':attribute sudah dipakai.',
            'image' => 'File harus berupa gambar.',
            'mimes' => 'Format gambar harus jpg atau png.',
            'img.max' => 'Ukuran gambar maksimal 1 MB.',
        ];
    }

    // Daftar produk + pencarian + filter kategori + pagination
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('s'), fn ($q) => $q->where('name', 'like', '%' . $request->s . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->orderByDesc('product_id')
            ->paginate(5)
            ->withQueryString();

        return view('admin.daftarproduk', [
            'title' => 'Daftar Produk',
            'products' => $products,
            'categories' => Category::all(),
        ]);
    }

    // Produk per kategori: memakai relasi hasMany (Category -> products)
    public function byCategory(Category $category)
    {
        return view('admin.daftarproduk', [
            'title' => 'Produk Kategori: ' . $category->category,
            'products' => $category->products()->with('category')->orderByDesc('product_id')->paginate(5),
            'categories' => Category::all(),
            'category' => $category,
        ]);
    }

    public function create()
    {
        return view('admin.inputproduk', [
            'title' => 'Input Produk',
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());
        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.inputproduk', [
            'title' => 'Edit ' . $product->name,
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules($product), $this->messages());
        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('img')) {
            $product->deleteLocalImage(); // hapus file lama
            $data['img'] = $request->file('img')->store('products', 'public');
        } else {
            unset($data['img']); // pertahankan gambar lama
        }

        $product->update($data);

                return redirect()->route('admin.products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->deleteLocalImage();
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Produk berhasil dihapus.');
    }
}