<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return view('admin.comment.index');
    }

    public function data()
    {
        $comment = Comment::orderBy('id', 'desc')->get();

        return datatables()
            ->of($comment)
            ->addIndexColumn()
            ->addColumn('user', function ($comment) {
                return $comment->user->name;
            })
            ->addColumn('product', function ($comment) {
                return $comment->product->name;
            })
            ->addColumn('date', function ($comment) {
                return $comment->created_at->format('d F Y');
            })
            ->addColumn('action', function ($comment) {
                return '
                <div style="display: flex; justify-content: center;">
                    <form method="POST" action="' . route('comment.destroy', $comment->id) . '" style="display: inline;">
                        ' . csrf_field() . method_field("DELETE") . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function destroy($id)
    {
        Comment::destroy($id);

        return redirect()->route('comment.index');
    }
}
