<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $category = Category::all();
        $product = Product::with('category')->get();
        return view('user.home', compact('category', 'product'));
    }

    public function detail($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if ($product !== null && $product->category !== null) {
            $relatedProduct = Product::with('category')
                ->where('category_id', $product->category->id)
                ->where('id', '!=', $product->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('user.product.detail', compact('product', 'relatedProduct'));
        } else {
            return view('user.pages.404');
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $product = Product::where('name', 'like', '%' . $search . '%')->get();

        if ($product->isNotEmpty()) {
            return view('user.pages.shop', compact('product', 'search'));
        } else {
            return view('user.pages.404');
        }
    }

    public function shop()
    {

        $product = Product::with('category')->paginate(9);
        return view('user.pages.shop', compact('product'));
    }

    public function testimoni()
    {
        return view('user.pages.testimoni');
    }

    public function contact()
    {
        return view('user.pages.contact');
    }
}
