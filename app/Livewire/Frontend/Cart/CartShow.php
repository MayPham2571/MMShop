<?php

namespace App\Livewire\Frontend\Cart;

use App\Models\Cart;
use Auth;
use Livewire\Component;

class CartShow extends Component
{
    public $cart;
    public $cartItems = []; // Initialize cart items
    public $selectedItems = []; // Track selected items
    public $totalPrice = 0;

    public function incrementQuantity(int $cartId) {

        $cartData = Cart::where('id',$cartId)->where('user_id', Auth::id())->first();
        if($cartData){

            if($cartData->productColor()->where('id',$cartData->product_color_id)->exists()){
                //Quantity of each color item
                $productColor = $cartData->productColor()->where('id',$cartData->product_color_id)->first();
                if($productColor->quantity > $cartData->quantity){
                    $cartData->increment('quantity');
                    $this->dispatch('message', 
                        text: 'Quantity updated',
                        type: 'success',
                        status: 200
                    );
                }else{
                    $this->dispatch('message', 
                        text: 'Only ' .$productColor->quantity.' Quantity Available',
                        type: 'warning',
                        status: 409
                    );
                }

            }else{
                //Normal product quantity
                if($cartData->product->quantity > $cartData->quantity){

                    $cartData->increment('quantity');
                    $this->dispatch('message', 
                        text: 'Quantity updated',
                        type: 'success',
                        status: 200
                    );
                }else{
                    $this->dispatch('message', 
                        text: 'Only ' .$cartData->product->quantity.' Quantity Available',
                        type: 'warning',
                        status: 409
                    );
                }

            }

        }else{
            $this->dispatch('message', 
                text: 'Something went wrong!',
                type: 'error',
                status: 404
            );
        }
    }

    public function decrementQuantity(int $cartId) {
        $cartData = Cart::where('id',$cartId)->where('user_id', Auth::id())->first();
        if($cartData){

            if($cartData->quantity > 1){
                $cartData->decrement('quantity');
                $this->dispatch('message', 
                        text: 'Quantity updated',
                        type: 'success',
                        status: 200
                    );

            }else{
                $this->dispatch('message', 
                        text: 'Quantity cannot be less than 1',
                        type: 'warning',
                        status: 200
                    );
            }

        }else{
            $this->dispatch('message', 
                text: 'Something went wrong!',
                type: 'error',
                status: 500
            );
        }
    }

    public function removeCartItem(int $cartId) {
        $cartRemoveData = Cart::where('user_id', Auth::id())->where('id', $cartId)->first();
        if($cartRemoveData){
            $cartRemoveData->delete();
            $this->dispatch('CartAddedUpdated');
            $this->dispatch('message', 
                text: 'Cart item remove successfully.',
                type: 'success',
                status: 200
            );
        }else{
            $this->dispatch('message', 
                text: 'Something went wrong',
                type: 'error',
                status: 500
            );
        }
    }
    public function getTotalPrice()
    {
        // \Log::info('Selected Items:', $this->selectedItems);

        $this->totalPrice = Cart::whereIn('id', $this->selectedItems)
        ->with('product') // Load related product
        ->get()
        ->sum(function ($cartItem) {
            return $cartItem->product->selling_price * $cartItem->quantity;
        });
        // \Log::info('Total Price Calculated: ' . $this->totalPrice);
    }

    public function updatedSelectedItems()
    {
    $this->getTotalPrice();
    }

    public function selectItem($itemId)
    {
        // Toggle item selection
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        } else {
            $this->selectedItems[] = $itemId;
        }
    }

    public function proceedToCheckout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Please select at least one item to proceed to checkout.');
            return;
        }

        // Store selected items in the session
        session(['selectedCartItems' => $this->selectedItems]);

        // Redirect to the checkout page
        return redirect()->route('checkout');
    }

    
    public function render()
    {
        $this->cart = Cart::where('user_id', Auth::id())->get();
        return view('livewire.frontend.cart.cart-show',[
            'cart' => $this->cart,
            'totalPrice' => $this->totalPrice,
        ]);
    }
}
