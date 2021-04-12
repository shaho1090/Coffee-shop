<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{

    /**
     * @return Application|ResponseFactory|Response
     */
    public function index()
    {
        return response([
            'products' => new ProductCollection(Product::all())
        ]);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $product = (new Product())->createNew($request->toArray());

        DB::commit();

        return response([
            'product' => new ProductResource($product)
        ]);
    }

    public function show(Product $product)
    {
        return response([
            'product' => new ProductResource($product)
        ]);
    }
}
