<?php

namespace App\Http\Controllers;

use App\Http\Filters\ControllerWithProducts;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;

class ProductController extends Controller
{
    use ControllerWithProducts;
    const PRODUCTS_PER_PAGE = 10;

    public function show(string $id): View
    {
        $product = Product::with('categories')
            ->with(['brand'])
            ->findOrFail($id);

        $images = $product->getMedia('images');
        $imageUrls = collect();
        $avifUrls = collect();

        if ($images->isEmpty()) {
            $imageUrls[] = $product->fallbackImageUrl();
            $avifUrls[] = $product->fallbackImageUrl();
        } else {
            foreach ($images as $image) {
                $imageUrls[] = $image->getUrl('hero');
                $avifUrls[] = $image->getUrl('hero-avif');
            }
        }

        return view('product', [
            'product' => $product,
            'avifUrls' => $avifUrls,
            'imageUrls' => $imageUrls,
            'otherProducts' => Product::limit(self::PRODUCTS_PER_PAGE)
                ->whereNot('id', '=', $id)
                ->with(['categories'])
                ->get()
        ]);
    }

    public function all(Request $request): View
    {
        $query = Product::with(['categories']);

        $brands = $this->getBrands($query);
        $colors = $this->getColors($query);
        $query = $this->filterQuery($request, $query);

        $minPrice = intval($query->min('price'));
        $maxPrice = round($query->max('price'), 0, PHP_ROUND_HALF_UP);

        return view('product-list', [
            'heading' => 'All Products',
            'products' => $query->paginate(self::PRODUCTS_PER_PAGE)->withQueryString(),
            'hiddenFields' => [],
            'brands' => $brands,
            'colors' => $colors,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

    private function getCheckedCategories(Request $request): array
    {
        $prefix = 'category-';
        $result = [];

        foreach (Category::all()->select(['id']) as $category) {
            if ($request->input($prefix . $category['id']) == '1') {
                $result[] = $category['id'];
            }
        }

        return $result;
    }

    public function create(\App\Http\Requests\StoreProductRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->safe()->except(['images']);

        $product = Product::create($validated);

        $categories = $this->getCheckedCategories($request);
        $product->categories()->sync($categories);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image !== null) {
                    try {
                        $product->addMedia($image)->toMediaCollection('images');
                    } catch (FileDoesNotExist|FileIsTooBig $e) {
                        Log::error($e);
                    }
                }
            }
        }

        return redirect('/product/' . $product->id);
    }

    public function delete(Product $product): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('delete', $product);

        $product->delete();
        return redirect()->route('admin.products');
    }

    public function new(): View
    {
        $brands = Brand::all();
        $categories = Category::all();

        return view('product.edit', [
            'create' => true,
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    public function update(Product $product, \App\Http\Requests\UpdateProductRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->safe()->except(['images']);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image !== null) {
                    try {
                        $product->addMedia($image)->toMediaCollection('images');
                    } catch (FileDoesNotExist|FileIsTooBig $e) {
                        Log::error($e);
                    }
                }
            }
        }

        $product->update($validated);

        $categories = $this->getCheckedCategories($request);
        $product->categories()->sync($categories);

        return redirect('/product/' . $product->id);
    }

    public function edit(Product $product): View
    {
        $brands = Brand::all();
        $categories = Category::all();

        return view('product.edit', [
            'create' => false,
            'product' => $product->load('brand'),
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }

    public function adminIndex(Request $request): View
    {
        Gate::authorize('create', Product::class);

        $query = Product::with(['brand', 'categories']);

        $brands = $this->getBrands($query);
        $colors = $this->getColors($query);
        $query = $this->filterQuery($request, $query);

        return view('admin.products', [
            'heading' => 'Manage Products',
            'products' => $query->paginate(20)->withQueryString(),
            'hiddenFields' => [],
            'brands' => $brands,
            'colors' => $colors,
        ]);
    }

    public function removeImage(Product $product, Request $request): \Illuminate\Http\RedirectResponse
    {
        Gate::authorize('update', $product);

        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $id = $validated['id'];

        try {
            $product->deleteMedia($id);
        } catch (MediaCannotBeDeleted $e) {
            Log::error($e);
        }

        return redirect()->back();
    }
}
