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
    private function getCategories($query, $categorySlug)
    {
        // Filter berdasarkan kategori (slug)
        if ($categorySlug && $categorySlug != '') {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        return $query;
    }

    public function index()
    {
        $category = Category::all();
        $product = Product::with('category')->get();
        return view('user.home', compact('category', 'product'));
    }

    public function detail($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        $comment = Comment::with('user')->where('product_id', $product->id)->get();

        $query = Product::with('category');

        // panggil fungsi getCategories
        $filter = $this->getCategories($query, $request->category);

        // Dapatkan kategori dengan jumlah produk
        $categories = Category::withCount('products')->get();

        if ($product !== null && $product->category !== null) {
            $relatedProduct = Product::with('category')
                ->where('category_id', $product->category->id)
                ->where('id', '!=', $product->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('user.product.detail', compact('product', 'relatedProduct', 'comment', 'categories'));
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

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }

    public function shop(Request $request)
    {
        $query = Product::with('category');
        // panggil fungsi getCategories
        $filter = $this->getCategories($query, $request->category);

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
        $order = Order::with('address')->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('user.pages.order', compact('order'));
    }
}
