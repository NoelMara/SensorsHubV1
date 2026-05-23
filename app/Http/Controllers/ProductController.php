<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->paginate(12);
        return view('shop.index', compact('products'));
    }
    
    public function show($id)
    {
        $product = Product::where('is_active', true)->findOrFail($id);
        return view('shop.show', compact('product'));
    }
}