<div>
    <div class="py-3 py-md-4 checkout">
        <div class="container">
            <h4>Checkout</h4>
            <hr>
            
            <div class="row">
                <!-- Total Amount Section -->
                <div class="col-md-12 mb-4">
                    <div class="shadow bg-white p-3">
                        <h4 class="text-primary">
                            Item Total Amount:
                            <span class="float-end">{{ number_format($totalPrice, 0, '.', ',') }} VND</span>
                        </h4>
                        <hr>
                        <small>* Items will be delivered in 3 - 5 days.</small>
                        <br />
                        <small>* Tax and other charges are included.</small>
                    </div>
                </div>

                <!-- Selected Cart Items -->
                <div class="col-md-12">
                    <div class="shadow bg-white p-3">
                        <h4 class="text-primary">Your Selected Items</h4>
                        <hr>
                        <ul class="list-group">
                            @foreach ($cartItems as $cartItem)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $cartItem->product->name }} 
                                    - {{ $cartItem->quantity }} x {{ number_format($cartItem->product->selling_price, 0, '.', ',') }} VND
                                    <span>{{ number_format($cartItem->product->selling_price * $cartItem->quantity, 0, '.', ',') }} VND</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="col-md-12">
                    <div class="shadow bg-white p-3">
                        <h4 class="text-primary">Basic Information</h4>
                        <hr>

                        <form wire:submit.prevent="placeOrder">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name</label>
                                    <input type="text" wire:model.defer="fullname" id="fullname" class="form-control" placeholder="Enter Full Name" />
                                    @error('fullname') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number</label>
                                    <input type="number" wire:model.defer="phone" id="phone" class="form-control" placeholder="Enter Phone Number" />
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email Address</label>
                                    <input type="email" wire:model.defer="email" id="email" class="form-control" placeholder="Enter Email Address" />
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Pin-code (Zip-code)</label>
                                    <input type="number" wire:model.defer="pincode" id="pincode" class="form-control" placeholder="Enter Pin-code" />
                                    @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Full Address</label>
                                    <textarea wire:model.defer="address" id="address" class="form-control" rows="2"></textarea>
                                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                
                                    <div class="col-md-12 mb-3" wire:ignore>
                                        {{-- <label>Select Payment Mode: </label> --}}
                                        <div class="d-md-flex align-items-start">
                                            <div class="nav col-md-3 flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                <button class="nav-link fw-bold" active id="cashOnDeliveryTab-tab" data-bs-toggle="pill" data-bs-target="#cashOnDeliveryTab" type="button" role="tab" aria-controls="cashOnDeliveryTab" aria-selected="true">Cash on Delivery</button>
                                                {{-- <button class="nav-link fw-bold" id="onlinePayment-tab" data-bs-toggle="pill" data-bs-target="#onlinePayment" type="button" role="tab" aria-controls="onlinePayment" aria-selected="false">Online Payment</button> --}}
                                            </div>
                                            <div class="tab-content col-md-9" id="v-pills-tabContent">
                                                <div class="tab-pane active show fade" id="cashOnDeliveryTab" role="tabpanel" aria-labelledby="cashOnDeliveryTab-tab" tabindex="0">
                                                    <h6>Cash on Delivery Method</h6>
                                                    <hr/>
                                                    <button type="button" wire:click="codOrder" class="btn btn-primary">Place Order (Cash on Delivery)</button>
    
                                                </div>
                                                {{-- <div class="tab-pane fade" id="onlinePayment" role="tabpanel" aria-labelledby="onlinePayment-tab" tabindex="0"> --}}
                                                    {{-- <h6>Online Payment Mode</h6> --}}
                                                    {{-- <hr/> --}}
                                                    {{-- <button type="button" class="btn btn-warning">Pay Now (Online Payment)</button> --}}
                                                    {{-- <div> --}}
                                                        {{-- <div id="paypal-button-container"></div> --}}
                                                    {{-- </div> --}}
                                                {{-- </div> --}}
                                                
                                            </div>
                                        </div>
    
                                    </div>
                                
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @push('scripts')

<script src="https://www.paypal.com/sdk/js?client-id=AW5-XpJ6msPUGDl6P2HdUJ8Rn8b--ekkRt7cqz9lbv206MyTMokX__UaD1kSiMr32f0cGIpRIznfOiUq&currency=USD"></script>

<script>
    window.paypal
    .Buttons({
        onClick: function () {
            const fields = ["fullname", "phone", "email", "pincode", "address"];
            let isValid = true;

            fields.forEach((field) => {
                const value = document.getElementById(field)?.value;
                if (!value) {
                    isValid = false;
                    alert(`Please fill in the ${field} field.`);
                } else {
                    @this.set(field, value); // Livewire sync
                }
            });

            if (!isValid) {
                return false;
            }
        },
        createOrder: async function () {
            try {
                const response = await fetch("{{ route('paypal.create') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        amount: Number("{{ $totalPrice }}"), // Ensure the amount is numeric
                    }),
                });

                const orderData = await response.json();

                if (orderData.id) {
                    return orderData.id; // Return PayPal Order ID
                } else {
                    console.error("Create Order Error Response:", orderData);
                    throw new Error("Failed to create PayPal order.");
                }
            } catch (error) {
                console.error("Error in createOrder:", error);
                alert("Error creating PayPal order. Please try again.");
                throw error; // Ensure the error bubbles up
            }
        },
        onApprove: async function (data) {
            try {
                const response = await fetch(
                    "{{ route('paypal.capture', ['orderID' => '__ORDER_ID__']) }}".replace(
                        "__ORDER_ID__",
                        data.orderID
                    ),
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                    }
                );

                const orderData = await response.json();

                if (orderData.id) {
                    alert("Payment successful! Thank you for your purchase.");
                    window.location.href = "/thank-you";
                } else {
                    throw new Error("Failed to capture PayPal order.");
                }
            } catch (error) {
                console.error("Error capturing PayPal order:", error);
                alert("Error capturing PayPal order. Please try again.");
            }
        },
        onError: function (err) {
            console.error("PayPal Button Error:", err);
            alert("An error occurred during the PayPal transaction.");
        },
    })
    .render("#paypal-button-container");


</script>


@endpush --}}
