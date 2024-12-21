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

        if ($product !== null) {
            return view('user.product.detail', compact('product'));
        } else {
            return view('user.pages.404');
        }
    }

    public function shop()
    {
        return view('user.pages.shop');
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
