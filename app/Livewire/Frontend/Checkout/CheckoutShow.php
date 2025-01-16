<?php

namespace App\Livewire\Frontend\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Orderitem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;



class CheckoutShow extends Component
{
    public $cartItems;
    public $totalPrice = 0;
    public $selectedItems = []; // Holds selected item IDs
    public $fullname, $email, $phone, $pincode, $address, $payment_method = NULL, $payment_id = NULL;

    protected $listeners = [
        'validationForAll'
    ];

    public function validationForAll(){
        $this->validate();
    }

    public function rules(){
        
        return [
            'fullname' => 'required|string|max:121',
            'email' => 'required|email|max:121',
            'phone' => 'required|string|max:11|min:10',
            'pincode' => 'required|numeric',
            'address' => 'required|string|max:500',
            // 'paymentMethod' => 'required|in:cod,online',
        ];
    }
    
    // public function placeOrder() {
        
    //     $this->validate();

    //     $order = Order::create([
    //         'user_id' => Auth::id(),
    //         'tracking_no' => 'mmshop-'.Str::random(10), 
    //         'fullname' => $this->fullname,
    //         'email' => $this->email,
    //         'phone' => $this->phone,
    //         'pincode' => $this->pincode,
    //         'address' => $this->address,
    //         'status_message' => 'in progress',
    //         'payment_method' => $this->payment_method,
    //         'payment_id' => $this->payment_id,
    //     ]);

    //     // Add the selected items to the OrderItem table
    //     foreach ($this->cartItems as $cartItem) {
    //         if (in_array($cartItem->id, $this->selectedItems)) {
    //             $orderItems = Orderitem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $cartItem->product_id, // Product ID from the cart
    //                 'product_color_id' => $cartItem->product_color_id, // If applicable
    //                 'quantity' => $cartItem->quantity,
    //                 'price' => $cartItem->product->selling_price, // Price of the product
    //         ]);
    //         }
    //     }
    //     // Reduce product quantity in the database
    //     if ($cartItem->productColor) {
    //         // If product has colors, reduce the stock of the specific color
    //         $cartItem->productColor->decrement('quantity', $cartItem->quantity);
    //     } else {
    //         // Reduce the stock of the main product
    //         $cartItem->product->decrement('quantity', $cartItem->quantity);
    //     }

    //     return $order;


    // // Remove selected items from the cart
    // // Cart::whereIn('id', $this->selectedItems)->delete();

    // }

    public function placeOrder()
{
    $this->validate();

    // Stock validation: Ensure enough stock is available before processing the order
    foreach ($this->cartItems as $cartItem) {
        if (in_array($cartItem->id, $this->selectedItems)) {
            if ($cartItem->productColor) {
                // Check stock for products with colors
                if ($cartItem->productColor->quantity < $cartItem->quantity) {
                    session()->flash('error', 'Not enough stock for ' . $cartItem->product->name . ' (Color: ' . $cartItem->productColor->color->name . ').');
                    return;
                }
            } elseif ($cartItem->product->quantity < $cartItem->quantity) {
                // Check stock for products without colors
                session()->flash('error', 'Not enough stock for ' . $cartItem->product->name . '.');
                return;
            }
        }
    }

    // Create the order
    $order = Order::create([
        'user_id' => Auth::id(),
        'tracking_no' => 'mmshop-' . Str::random(10),
        'fullname' => $this->fullname,
        'email' => $this->email,
        'phone' => $this->phone,
        'pincode' => $this->pincode,
        'address' => $this->address,
        'status_message' => 'in progress',
        'payment_method' => $this->payment_method,
        'payment_id' => $this->payment_id,
    ]);

    // Add selected items to the OrderItem table and reduce stock
    foreach ($this->cartItems as $cartItem) {
        if (in_array($cartItem->id, $this->selectedItems)) {
            // Add item to OrderItem
            Orderitem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_color_id' => $cartItem->product_color_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->selling_price,
            ]);

            // Reduce the stock of the product or product color
            if ($cartItem->productColor) {
                // For products with colors, reduce the stock of the specific color
                $cartItem->productColor->decrement('quantity', $cartItem->quantity);
            } 
                // For products without colors, reduce the stock of the main product
                $cartItem->product->decrement('quantity', $cartItem->quantity);
            
        }
    }

    return $order;
}


    public function codOrder() {
        
        $this->payment_method = 'Cash on Delivery';

        $codOrder = $this->placeOrder();

        if($codOrder){

            Cart::whereIn('id', $this->selectedItems)->delete();

            session()->flash('message','Order Placed Successfully!');
            $this->dispatch('message', 
                text: 'Order Placed Successfully',
                type: 'success',
                status: 200
            );
            return redirect()->to('thank-you');

        }else{
            $this->dispatch('message', 
                text: 'Something went wrong',
                type: 'error',
                status: 500
            );
        }
    }


    public function mount()
    {
        // Retrieve selected items from session
        $this->selectedItems = session('selectedCartItems', []);

        if (empty($this->selectedItems)) {
            // Redirect back to cart if no items are selected
            return redirect()->route('cart')->with('error', 'No items selected for checkout.');
        }

        // Fetch selected cart items
        $this->cartItems = Cart::whereIn('id', $this->selectedItems)
            ->with('product') // Load related products
            ->get();

        // Ensure cartItems is a collection
        $this->cartItems = collect($this->cartItems);

        // Calculate total price
        $this->calculateTotalPrice();
    }


    public function toggleSelection($itemId)
    {
        // Toggle item selection
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        } else {
            $this->selectedItems[] = $itemId;
        }

        // Recalculate total price
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        // Ensure cartItems is a collection
        if (!($this->cartItems instanceof \Illuminate\Support\Collection)) {
            $this->cartItems = collect($this->cartItems);
        }

        // Calculate total price
        $this->totalPrice = $this->cartItems->sum(function ($cartItem) {
            return $cartItem->product->selling_price * $cartItem->quantity;
        });
    }

    public function checkout()
    {
        // Emit total price to be used on the checkout page
        $this->emit('totalPriceForCheckout', $this->totalPrice);
    }

    public function render()
    {
         /** @disregard P1012 */
        $this->fullname = auth()->user()->name;
         /** @disregard P1012 */
        $this->email = auth()->user()->email;
        // Fetch all cart items for rendering
        $cartItems = Cart::where('user_id', Auth::id())->get();

        return view('livewire.frontend.checkout.checkout-show', [
            'cartItems' => $cartItems,
            'totalPrice' => $this->totalPrice,
        ]);
    }
}
