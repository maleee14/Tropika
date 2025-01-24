<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function updateCartItemQuantity($rowId, $quantityChange)
    {
        $cart = Cart::get($rowId);

        $newQuantity = $cart->qty + $quantityChange;

        if ($newQuantity < 1) {
            $newQuantity = 1;
            return redirect()->back()->with('alert', 'Minimal Harus Ada 1 Produk');
        }

        $newTotalAmount = $newQuantity * $cart->price;

        $options = $cart->options->toArray();
        $options['total_amount'] = $newTotalAmount;

        Cart::update($rowId, [
            'qty' => $newQuantity,
            'options' => $options
        ]);

        return redirect()->back();
    }

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
            return $this->updateCartItemQuantity($cartItem->rowId, $quantity);
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

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function increaseItem($rowId)
    {
        session()->flash('success', 'Produk berhasil ditambahkan');
        return $this->updateCartItemQuantity($rowId, 1);
    }

    public function decreaseItem($rowId)
    {
        session()->flash('success', 'Produk berhasil dikurangi');
        return $this->updateCartItemQuantity($rowId, -1);
    }

    public function destroy($id)
    {
        Cart::remove($id);
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function checkout()
    {
        $cartItem  = Cart::content();
        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        $grandTotal = floatval(str_replace(',', '', Cart::priceTotal()));

        if ($cartItem->isEmpty()) {
            return view('user.pages.404');
        } else {
            return view('user.cart.checkout', compact('cartItem', 'subtotal', 'grandTotal'));
        }
    }
}
