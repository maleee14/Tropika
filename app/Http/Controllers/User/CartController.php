<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItem = Cart::content();
        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        // dd($cartItem);
        return view('user.cart.index', compact('cartItem', 'subtotal'));
    }

    public function addCart(Request $request)
    {
        $product = Product::findOrFail($request->input('product_id'));
        $quantity = $request->input('quantity');

        // Check if the product already exists in the cart
        $cartItem = Cart::content()->where('id', $product->id)->first();

        if ($cartItem) {
            // Update the existing cart item
            $newQty = $cartItem->qty + $quantity;
            $newTotalAmount = $newQty * $product->price;

            // Preserve existing options and update total_amount
            $options = $cartItem->options->toArray();
            $options['total_amount'] = $newTotalAmount;

            Cart::update($cartItem->rowId, [
                'qty' => $newQty,
                'options' => $options
            ]);
        } else {
            // Add new item to the cart
            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $quantity,
                'price' => $product->price,
                'weight' => 0,
                'options' => [
                    'image' => $product->image,
                    'total_amount' => $product->price * $quantity
                ]
            ]);
        }

        return redirect()->back();
    }

    public function increaseItem($id)
    {
        //
    }

    public function decreaseItem($id)
    {
        //
    }

    public function destroy($id)
    {
        Cart::remove($id);
        return redirect()->back();
    }
}
