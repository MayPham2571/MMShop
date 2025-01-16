<?php

namespace App\Livewire\Frontend;

use App\Models\Wishlist;
use Auth;
use Livewire\Component;

class WishlistShow extends Component
{
    public function removeWishlistItem(int $wishlistId){

        Wishlist::where('user_id', Auth::id())->where('id',$wishlistId)->delete();
        $this->dispatch('wishlistAddedUpdated');

        $this->dispatch('message',
        text: 'Wishlist item removed successfully!',
        type: 'success',
    
    );
    }

    public function render()
    {
        $wishlist = Wishlist::where('user_id',Auth::id())->get();
        return view('livewire.frontend.wishlist-show',[
            'wishlist' => $wishlist
        ]);
    }
}
