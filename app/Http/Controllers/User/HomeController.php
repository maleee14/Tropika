<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
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
        $comment = Comment::with('user')->where('product_id', $product->id)->get();

        if ($product !== null && $product->category !== null) {
            $relatedProduct = Product::with('category')
                ->where('category_id', $product->category->id)
                ->where('id', '!=', $product->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('user.product.detail', compact('product', 'relatedProduct', 'comment'));
        } else {
            return view('user.pages.404');
        }
    }

    public function createComment(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        Comment::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->input('product_id'),
            'content' => $request->content
        ]);

        return redirect()->back();
    }

    public function shop(Request $request)
    {
        // Mulai query untuk produk
        $query = Product::with('category');

        // Filter berdasarkan kategori (slug)
        if ($request->has('category') && $request->category != '') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter berdasarkan pencarian (nama produk)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Dapatkan hasil dengan paginasi
        $products = $query->paginate(9);

        // Dapatkan kategori dengan jumlah produk
        $categories = Category::withCount('products')->get();

        // Kirim data ke view
        if ($products->isNotEmpty()) {
            return view('user.pages.shop', compact('products', 'categories'));
        } else {
            return view('user.pages.404');
        }
    }


    public function testimoni()
    {
        return view('user.pages.testimoni');
    }

    public function contact()
    {
        return view('user.pages.contact');
    }

    public function order()
    {
        $order = Order::with('user')->where('user_id', auth()->user()->id)->get();
        return view('user.pages.order', compact('order'));
    }
}
