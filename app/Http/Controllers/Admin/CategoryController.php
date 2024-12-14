<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        return view('admin.category.index', compact('category'));
    }

    public function data()
    {
        $category = Category::orderBy('name', 'asc')->get();

        return datatables()
            ->of($category)
            ->addIndexColumn()
            ->addColumn('image', function ($category) {
                return '<img src="' . url('storage/category/' . $category->image) . '" alt="' . $category->name . '" style="width: 100px; height: auto;">';
            })
            ->addColumn('action', function ($category) {
                return '
                <div style="display: flex; justify-content: center;">
                    <button type="button" onclick="editForm(`' . route('category.update', $category->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Edit</button>
                    <form method="POST" action="' . route('category.destroy', $category->id) . '" style="display: inline;">
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
            'name' => 'required|unique:categories,name',
            'image' => 'required|image',
        ]);

        $file = $request->file('image');
        $path = time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();

        Storage::disk('category')->put($path, file_get_contents($file));

        Category::create([
            'name' => $request->name,
            'image' => $path,
        ]);

        return response()->json('Data Berhasil Di Simpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        return response()->json($category);
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
        $category = Category::find($id);

        $request->validate([
            'name' => 'required',
            'image' => 'sometimes|image',
        ]);

        $category->name = $request->name;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();

            Storage::disk('category')->put($path, file_get_contents($file));

            if ($category->image) {
                Storage::disk('category')->delete($category->image);
            }

            $category->image = $path;
        }

        $category->update();

        return response()->json('Data Berhasil Di Simpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('category.index');
    }
}
