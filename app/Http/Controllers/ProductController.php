<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $products = Product::with(['category','skus'])
            ->when(request('category'), function ($query) {
                $query->whereHas('category', function ($query) {
                    $query->where('name','like','%'.request('category').'%');
                });
            })
            ->when(request('product'), function ($query) {
                $query->where('name', 'like', '%'.request('product').'%');
            })
            ->withCount('skus')
            ->orderBy('id', 'desc')
            ->paginate(10);
        $categories = Category::select('id','name')->get();
        return Inertia::render('product/Index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());
            $product->skus()->createMany($request->sku);

            return back();

        }catch (\Exception $e) {
            return back()->with('error', 'Error creating product');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}
