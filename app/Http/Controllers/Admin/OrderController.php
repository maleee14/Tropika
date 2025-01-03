<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            ->addColumn('address', function ($order) {
                return $order->address->address . ', ' . $order->address->city . ', ' . $order->address->zip_code;
            })
            ->addColumn('phone', function ($order) {
                return $order->address->phone;
            })
            ->addColumn('grand_total', function ($order) {
                return 'Rp ' . number_format($order->grand_total, 0, ',', '.');
            })
            ->addColumn('action', function ($order) {
                return '<button type="button" onclick="showDetail(`' . route('order.show', $order->id) . '`)" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> Detail</button>';
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $detail = OrderDetail::with('product')->where('order_id', $id)->get();
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('name', function ($detail) {
                return $detail->product->name;
            })
            ->addColumn('unit_amount', function ($detail) {
                return 'Rp ' . number_format($detail->unit_amount, 0, ',', '.');
            })
            ->addColumn('quantity', function ($detail) {
                return $detail->quantity;
            })
            ->addColumn('total_amount', function ($detail) {
                return 'Rp ' . number_format($detail->total_amount, 0, ',', '.');
            })
            ->make(true);
    }
}
