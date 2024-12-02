<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Checkout</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Checkout</li>
    </ol>
</div>
<!-- Single Page Header End -->


<!-- Checkout Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Checkout    details</h1>
        <form action="#">
            <div class="row g-5">

                <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Products</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamically generated cart items -->
                                <?php foreach ($cartItems as $item): 
                                    $total = $item['price'] * $item['quantity']?>  
                                <tr id="cart-item-<?= $item['product_id']; ?>">
                                    <th scope="row">
                                        <div class="d-flex align-items-center">
                                        <img src="<?= base_url($item['product_image']) ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">
                                        </div>
                                    </th>
                                    <td>
                                        <p class="mb-0 mt-4"><?= $item['product_name'] ?></p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4"><?= $item['price'] ?>$</p>
                                    </td>
                                    <td>
                                        <div class="input-group quantity mt-4" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-minus rounded-circle bg-light border" onclick="updateQuantity(<?= $item['product_id']; ?>, 'decrement')">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" class="quantity-input-<?= $item['product_id']; ?> form-control form-control-sm text-center border-0" value="<?= $item['quantity']; ?>" readonly>
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-plus rounded-circle bg-light border" onclick="updateQuantity(<?= $item['product_id']; ?>, 'increment')">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4 total"><?php echo ($total); ?> $</p>
                                    </td>
                                    <td>
                                        <button class="btn btn-md rounded-circle bg-light border mt-4" onclick="removeItemFromCart(<?= $item['product_id']; ?>)">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                                <tr>
                                    <th scope="row">
                                    </th>
                                    <td class="py-5"></td>
                                    <td class="py-5"></td>
                                    <td class="py-5">
                                        <p class="mb-0 text-dark py-3">Subtotal</p>
                                    </td>
                                    <td colspan="3" class="py-5">
                                        <div class="py-3 border-bottom border-top">
                                            <p class="mb-0 text-dark total subtotal">$414.00</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr colspan="3">
                                    <th scope="row">
                                    </th>
                                    <td class="py-5"></td>
                                    <td class="py-5"></td>
                                    <td class="py-5">
                                        <p class="mb-0 text-dark py-3">Shipping</p>                                        
                                    </td>
                                    <td colspan="3" class="py-5">
                                        <div class="py-3 border-bottom border-top shipping-rate">
                                            <p class="mb-0 text-dark">$0.00</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                    </th>
                                    <td class="py-5"></td>
                                    <td class="py-5"></td>
                                    <td class="py-5">
                                        <p class="mb-0 text-dark text-uppercase py-3">TOTAL</p>
                                    </td>

                                    <td colspan="3" class="py-5">
                                        <div class="py-3 border-bottom border-top">
                                            <p class="mb-0 text-dark grand-total">$0.00</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-4 border-bottom py-3">
                        <div class="col-12">
                            <div>
                                <h2 class="payment-method">
                                    Choose a payment method
                                </h2>
                            </div>
                            <div class="form-check text-start my-3">
                                <input type="radio" class="form-check-input bg-primary border-0" id="Delivery-1" name="payment_method" value="COD">
                                <label class="form-check-label" for="Delivery-1">Cash On Delivery</label>
                            </div>
                            <div class="form-check text-start my-3">
                                <input type="radio" class="form-check-input bg-primary border-0" id="Paypal-1" name="payment_method" value="Rayzorpay">
                                <label class="form-check-label" for="Paypal-1">Rayzorpay</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                        <button type="button" id="place-order-btn" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Checkout Page End -->
