<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
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
            'payment_method' => 'required|in:transfer,check,cod,paypal',
        ]);

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->payment_method = $request->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->notes = $request->notes;
        $order->grand_total = floatval(str_replace(',', '', Cart::priceTotal()));
        $order->save();

        $address = new Address();
        $address->order_id = $order->id;
        $address->firstname = $request->firstname;
        $address->lastname = $request->lastname;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->zip_code = $request->zip_code;
        $address->phone = $request->phone;
        $address->save();


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
