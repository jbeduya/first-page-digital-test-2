<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPostRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        return response()->json(Product::with('stocks')->get());
    }

    public function show($id) {
        return response()->json(Product::with('stocks')->find($id));
    }

    public function store(ProductPostRequest $request) {
        $created = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return response()->json($created);
    }
}
