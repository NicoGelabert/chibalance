<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::query();

        if (request()->has('category') && request('category')) {
            $categorySlug = request('category');
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        return request()->expectsJson()
            ? response()->json([
                'products' => $query->where('published', 1)
                    ->with(['categories', 'images']) // Asegurar que se cargan las relaciones
                    ->get()
                    ->map(function ($product) {
                        return array_merge(
                            $product->toArray(),
                            [
                                'categories' => $product->categories,
                                'prices' => $product->prices,
                                'image_url' => $product->images->first()->url ?? 'storage/common/noimage.png', // Primera imagen o default
                            ]
                        );
                    })
            ])
            : $this->renderProducts($query);
    }

    public function byCategory(Category $category)
    {
        $categories = Category::getAllChildrenByParent($category);

        $query = Product::query()
            ->select('products.*')
            ->join('product_categories AS pc', 'pc.product_id', 'products.id')
            ->whereIn('pc.category_id', array_map(fn($c) => $c->id, $categories));

        return $this->renderProducts($query);
    }

    public function view(Category $category, Product $product)
    {
        $product->load(['prices', 'categories', 'images']);
        $products = Product::where('published',true)->get();
        $categorySlug = $product->categories->first()->slug ?? null;
        return view('product.view', [
            'product' => $product,
            'category' => $category,
            'products' => $products,
            'categorySlug' => $categorySlug
        ]);
    }

    private function renderProducts(Builder $query)
    {
        $search = \request()->get('search');
        $sort = \request()->get('sort', '-updated_at');

        if ($sort) {
            $sortDirection = 'asc';
            if ($sort[0] === '-') {
                $sortDirection = 'desc';
            }
            $sortField = preg_replace('/^-?/', '', $sort);

            $query->orderBy($sortField, $sortDirection);
        }
        $products = $query
            ->where('published', '=', 1)
            ->where(function ($query) use ($search) {
                /** @var $query \Illuminate\Database\Eloquent\Builder */
                $query->where('products.title', 'like', "%$search%")
                    ->orWhere('products.description', 'like', "%$search%");
            })

            ->paginate(5);

        return view('product.index', [
            'products' => $products
        ]);

    }

}
