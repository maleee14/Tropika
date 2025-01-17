<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.order.index');
    }

    public function data()
    {
        $order = Order::with('user')->get();
        return datatables()
            ->of($order)
            ->addColumn('name', function ($order) {
                return $order->address->firstname . ' ' . $order->address->lastname;
            })
            ->addColumn('phone', function ($order) {
                return $order->address->phone;
            })
            ->addColumn('grand_total', function ($order) {
                return 'Rp ' . number_format($order->grand_total, 0, ',', '.');
            })
            ->addColumn('action', function ($order) {
                return '<a href="' . route('order.show', $order->id) . '" type="button" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = OrderDetail::with('product')->where('order_id', $id)->get();
        $addresses = Address::where('order_id', $id)->first();

        return view('admin.order.detail', compact('detail', 'addresses'));
    }
}
