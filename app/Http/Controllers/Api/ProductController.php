<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // GET /api/products
    public function index()
    {
        $products = Product::with('category')->paginate(10);

        return ProductResource::collection($products);
    }

    // POST /api/products
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'img' => $path,
            'slug' => $this->uniqueSlug($validated['name']),
        ]);

        return (new ProductResource($product->load('category')))
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/products/{product}
    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    // PUT/PATCH /api/products/{product}
    // Catatan: upload file lewat PUT tidak terbaca PHP, gunakan POST + field _method=PUT
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('img')) {
            $product->deleteLocalImage();
            $product->img = $request->file('img')->store('products', 'public');
        }

        $product->fill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        if ($product->isDirty('name')) {
            $product->slug = $this->uniqueSlug($validated['name'], $product->product_id);
        }
        $product->save();

        return new ProductResource($product->load('category'));
    }

    // DELETE /api/products/{product}
    public function destroy(Product $product)
    {
        $product->deleteLocalImage();
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 2;

        while (Product::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('product_id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
