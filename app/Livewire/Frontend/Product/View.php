<?php

namespace App\Livewire\Frontend\Product;

use App\Models\Cart;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth;

class View extends Component
{
    public $category, $product, $prodColorSelectedQuantity, $quantityCount = 1, $productColorId;

    public function addToWishList($productId){
        
        if(Auth::check()){

             
            if(Wishlist::where('user_id',Auth::id())->where('product_id',$productId)->exists()){
                $this->dispatch('message', 
                    text: 'Already added to your wishlist.',
                    type: 'warning',
                    status: 409
                );
                return false;
            }
             
            else{
            
                $wishlist = Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
                $this->dispatch('wishlistAddedUpdated');
                $this->dispatch('message', 
                    text: 'Wishlist added successfully!',
                    type: 'success',
                    status: 200
                );
            }

        }else{

            $this->dispatch('message', 
                text: 'Please, Login to continue',
                type: 'notify',
                status: 401
            );
            return false;
        }
    }

    public function colorSelected($productColorId){

        // dd($productColorId);
        $this->productColorId = $productColorId;
        $productColor = $this->product->productColors()->where('id', $productColorId)->first();
        $this->prodColorSelectedQuantity = $productColor->quantity;

        if($this->prodColorSelectedQuantity == 0){
            $this->prodColorSelectedQuantity = 'outOfStock';

        }
    }

    public function incrementQuantity() {

        if($this->quantityCount < 10){
            $this->quantityCount++;
        }
        
    } 
    
    public function decrementQuantity() {
        if($this->quantityCount > 1){
            $this->quantityCount--;
        }
        
    }

//     public function addToCart(int $productId)
// {
//     if (Auth::check()) {
//         // Check if the product exists and is active
//         if ($this->product->where('id', $productId)->where('status', '0')->exists()) {
//             // Check for product color quantity & insert into cart
//             if ($this->product->productColors()->count() > 1) {
//                 if ($this->prodColorSelectedQuantity !== NULL) {
//                     $productColor = $this->product->productColors()->where('id', $this->productColorId)->first();

//                     if ($productColor && $productColor->quantity > 0) {
//                         if ($productColor->quantity >= $this->quantityCount) {
//                             // Insert product with selected color into cart
//                             Cart::create([
//                                 'user_id' => Auth::id(),
//                                 'product_id' => $productId,
//                                 'product_color_id' => $this->productColorId,
//                                 'quantity' => $this->quantityCount,
//                             ]);
//                             $this->dispatch('message', 
//                                 text: 'Product added to cart.',
//                                 type: 'success',
//                                 status: 200
//                             );
//                         } else {
//                             $this->dispatch('message', 
//                                 text: 'Only ' . $productColor->quantity . ' quantity available.',
//                                 type: 'warning',
//                                 status: 404
//                             );
//                         }
//                     } else {
//                         $this->dispatch('message', 
//                             text: 'Out of Stock',
//                             type: 'warning',
//                             status: 404
//                         );
//                     }
//                 } else {
//                     $this->dispatch('message', 
//                         text: 'Please select a product color.',
//                         type: 'info',
//                         status: 409
//                     );
//                 }
//             } else {
//                 // For products without color options
//                 if ($this->product->quantity > 0) {
//                     if ($this->product->quantity >= $this->quantityCount) {
//                         // Insert product into cart
//                         Cart::create([
//                             'user_id' => Auth::id(),
//                             'product_id' => $productId,
//                             'quantity' => $this->quantityCount,
//                         ]);
//                         $this->dispatch('message', 
//                             text: 'Product added to cart.',
//                             type: 'success',
//                             status: 200
//                         );
//                     } else {
//                         $this->dispatch('message', 
//                             text: 'Only ' . $this->product->quantity . ' quantity available.',
//                             type: 'warning',
//                             status: 404
//                         );
//                     }
//                 } else {
//                     $this->dispatch('message', 
//                         text: 'Out of Stock',
//                         type: 'warning',
//                         status: 404
//                     );
//                 }
//             }
//         } else {
//             $this->dispatch('message', 
//                 text: 'Product does not exist.',
//                 type: 'warning',
//                 status: 404
//             );
//         }
//     } else {
//         $this->dispatch('message', 
//             text: 'Please login to add to cart.',
//             type: 'info',
//             status: 409
//         );
//     }
// }

public function addToCart(int $productId)
{
    if (Auth::check()) {
        // Check if the product exists and is active
        if ($this->product->where('id', $productId)->where('status', '0')->exists()) {
            // Check for product with multiple colors
            if ($this->product->productColors()->count() > 1) {
                if ($this->productColorId === null) {
                    $this->dispatch('message', 
                        text: 'Please select a product color.',
                        type: 'info',
                        status: 409
                    );
                    return;
                }

                $productColor = $this->product->productColors()->where('id', $this->productColorId)->first();

                if ($productColor && $productColor->quantity > 0) {
                    if ($productColor->quantity >= $this->quantityCount) {
                        // Check if the item with this color already exists in the cart
                        $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->where('product_color_id', $this->productColorId)
                            ->first();

                        if ($cartItem) {
                            // Increase quantity if the item already exists in the cart
                            $cartItem->increment('quantity', $this->quantityCount);
                            $this->dispatch('message', 
                                text: 'Product quantity updated in cart.',
                                type: 'success',
                                status: 200
                            );
                        } else {
                            // Create a new cart item
                            Cart::create([
                                'user_id' => Auth::id(),
                                'product_id' => $productId,
                                'product_color_id' => $this->productColorId,
                                'quantity' => $this->quantityCount,
                            ]);
                            $this->dispatch('CartAddedUpdated');
                            $this->dispatch('message', 
                                text: 'Product added to cart.',
                                type: 'success',
                                status: 200
                            );
                        }
                    } else {
                        $this->dispatch('message', 
                            text: 'Only ' . $productColor->quantity . ' quantity available.',
                            type: 'warning',
                            status: 404
                        );
                    }
                } else {
                    $this->dispatch('message', 
                        text: 'Out of Stock.',
                        type: 'warning',
                        status: 404
                    );
                }
            } else {
                // For products without color options
                if ($this->product->quantity > 0) {
                    if ($this->product->quantity >= $this->quantityCount) {
                        // Check if the item already exists in the cart
                        $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();

                        if ($cartItem) {
                            // Increase quantity if the item already exists in the cart
                            $cartItem->increment('quantity', $this->quantityCount);
                            $this->dispatch('message', 
                                text: 'Product quantity updated in cart.',
                                type: 'success',
                                status: 200
                            );
                        } else {
                            // Create a new cart item
                            Cart::create([
                                'user_id' => Auth::id(),
                                'product_id' => $productId,
                                'quantity' => $this->quantityCount,
                            ]);
                            $this->dispatch('CartAddedUpdated');
                            $this->dispatch('message', 
                                text: 'Product added to cart.',
                                type: 'success',
                                status: 200
                            );
                        }
                    } else {
                        $this->dispatch('message', 
                            text: 'Only ' . $this->product->quantity . ' quantity available.',
                            type: 'warning',
                            status: 404
                        );
                    }
                } else {
                    $this->dispatch('message', 
                        text: 'Out of Stock.',
                        type: 'warning',
                        status: 404
                    );
                }
            }
        } else {
            $this->dispatch('message', 
                text: 'Product does not exist.',
                type: 'warning',
                status: 404
            );
        }
    } else {
        $this->dispatch('message', 
            text: 'Please login to add to cart.',
            type: 'info',
            status: 409
        );
    }
}


    public function mount($category, $product) {
        $this->category = $category;
        $this->product = $product;
    }
    public function render()
    {
        return view('livewire.frontend.product.view',[
            'category' => $this->category,
            'product' => $this->product
        ]);

    }
}
