<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all()->pluck('name', 'id');
        return view('admin.product.index', compact('category'));
    }

    public function data()
    {
        $product = Product::orderBy('name', 'asc')->get();

        return datatables()
            ->of($product)
            ->addIndexColumn()
            ->addColumn('image', function ($product) {
                return '<img src="' . url('storage/' . $product->image) . '" alt="' . htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') . '" style="width: 100px; height: auto;">';
            })
            ->addColumn('category', function ($product) {
                return $product->category->name;
            })
            ->addColumn('action', function ($product) {
                return '
                <div style="display: flex; justify-content: center;">
                    <button type="button" onclick="editForm(`' . route('product.update', $product->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</button>
                    <form method="POST" action="' . route('product.destroy', $product->id) . '" style="display: inline;">
                        ' . csrf_field() . method_field("DELETE") . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
                ';
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
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
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|unique:products,name',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required|image',
            'description' => 'required',
        ]);

        $file = $request->file('image');
        $path = time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();

        Storage::disk('public')->put($path, file_get_contents($file));

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $path,
            'description' => $request->description,
        ]);

        return response()->json('Data Berhasil Di Simpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'sometimes|image',
            'description' => 'required',
        ]);

        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($path, file_get_contents($file));

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->image = $path;
        }

        $product->description = $request->description;
        $product->update();

        return response()->json('Data Berhasil Di Simpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('product.index');
    }
}
