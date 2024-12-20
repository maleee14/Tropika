<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $cartItem = Cart::content();

        $request->validate([
            'firstname' => 'required|min:3',
            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
        ]);

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'phone' => $request->phone,
            'notes' => $request->notes,
            'grand_total' => floatval(str_replace(',', '', Cart::priceTotal())),
        ]);

        foreach ($cartItem as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->qty,
                'unit_amount' => $item->price,
                'total_amount' => $item->options->total_amount,
            ]);

            $produk = Product::find($item->id);
            $produk->stock -= $item->qty;
            $produk->update();
        }

        Cart::destroy();

        return response()->json('berhasil');
    }
}
