(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);


    // Fixed Navbar
    $(window).scroll(function () {
        if ($(window).width() < 992) {
            if ($(this).scrollTop() > 55) {
                $('.fixed-top').addClass('shadow');
            } else {
                $('.fixed-top').removeClass('shadow');
            }
        } else {
            if ($(this).scrollTop() > 55) {
                $('.fixed-top').addClass('shadow').css('top', -55);
            } else {
                $('.fixed-top').removeClass('shadow').css('top', 0);
            }
        } 
    });
    
    
   // Back to top button
   $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Testimonial carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 2000,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:1
            },
            992:{
                items:2
            },
            1200:{
                items:2
            }
        }
    });


    // vegetable carousel
    $(".vegetable-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });


    // Modal Video
    $(document).ready(function () {
        var $videoSrc;
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
        });
        console.log($videoSrc);

        $('#videoModal').on('shown.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
        })

        $('#videoModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc);
        })
    });

})(jQuery);


//Cart functionalities
function addToCart(productId, quantity, price) {
    // Validate if quantity is set
    if (!quantity || quantity <= 0) {
        console.error("Invalid quantity");
        return;
    }

    // Hide the "Add to Cart" button and show the quantity control
    document.querySelectorAll('.add-to-cart-btn-' + productId).forEach(function(button) {
        button.classList.add('d-none'); // Hides all buttons with that class
    });

    document.querySelectorAll('.quantity-control-' + productId).forEach(function(control) {
        control.classList.remove('d-none'); // Shows the quantity control
    });

    document.querySelectorAll('.cardCount').forEach(function(cartCount) {
        cartCount.classList.remove('d-none'); // Shows the cart count
    });
    
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
    
    xhr.open('POST', '/cart/add/' + productId, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    // Define what to do when the request is successful
    xhr.onload = function () {
        if (xhr.status === 200) {
            updateCartCount(1); // Increment by 1 when item is added
            console.log('Product added to cart:', xhr.responseText);
        } else {
            console.error('Error adding product to cart:', xhr.statusText);
        }
    };
    
    // Send the request with data
    var data = 'quantity=' + encodeURIComponent(quantity) + '&price=' + encodeURIComponent(price);
    xhr.send(data);
}

function updateCartCount(change) {
    // Get the cart count element
    var cartCountElement = document.querySelector('.cardCount');
    var currentCount = parseInt(cartCountElement.textContent) || 0; // default to 0

    // Update the cart count
    var updatedCount = currentCount + change;
    updatedCount = Math.max(0, updatedCount); // Ensure the count doesn't go below 0

    // Update the cart count on the page
    cartCountElement.textContent = updatedCount;

    // If the cart count is 0, hide the card count display
    if (updatedCount === 0) {
        cartCountElement.classList.add('d-none');
    } else {
        cartCountElement.classList.remove('d-none');
    }
}

function updateQuantity(productId, cost, action) {
    const quantityInput = document.querySelector('.quantity-input-' + productId);

    let currentQuantity = parseInt(quantityInput.value) || 1;
    if (action === 'increment') {
        currentQuantity++;
        updateCartCount(1);
    } else if (action === 'decrement' && currentQuantity > 1) {
        currentQuantity--;
        updateCartCount(-1);
    } else if (action === 'decrement' && currentQuantity === 1) {
        const addToCartBtn = document.querySelectorAll('.add-to-cart-btn-' + productId);
        const quantityControl = document.querySelectorAll('.quantity-control-' + productId);

        if (addToCartBtn && quantityControl) {
            addToCartBtn.forEach(function(button) {
                button.classList.remove('d-none');
            });
            
            quantityControl.forEach(function(control) {
                control.classList.add('d-none');
            });
        }

        removeItemFromCart(productId);
        return;
    }
    document.querySelectorAll('.quantity-input-' + productId).forEach(function(element) {
        element.value = currentQuantity;
        console.log(element.value);  // Log the value of each input
    });
    
    // Update row total dynamically
    const priceElement = document.querySelector(`#cart-item-${productId} td:nth-child(3)`);
    const rowTotalElement = document.querySelector(`#cart-item-${productId} .total`);
    if (priceElement && rowTotalElement) {
        const price = parseFloat(priceElement.innerText.replace('$', '').trim());
        rowTotalElement.innerText = `${(price * currentQuantity).toFixed(2)} $`;
    }

    recalculateSubtotal();

    updateCartItem(productId, currentQuantity, cost);  // Backend call
}

function recalculateSubtotal() {
    var totalSum = 0;

    // Loop through all total elements (excluding the subtotal)
    document.querySelectorAll('.total:not(.subtotal)').forEach(function (totalElement) {
        var value = parseFloat(totalElement.innerText.replace('$', '').trim());
        totalSum += value;
    });

    // Determine the shipping cost
    var shippingFee = totalSum < 20 ? 5 : 0;

    // Calculate the grand total (totalSum + shippingFee)
    var grandTotal = totalSum + shippingFee;

    // Update the subtotal (totalSum) on the page
    var subtotalElement = document.querySelector('.subtotal');
    if (subtotalElement) {
        subtotalElement.innerText = '$' + totalSum.toFixed(2);
    }

    // Update the shipping fee on the page
    var shippingElement = document.querySelector('.shipping-rate');
    if (shippingElement) {
        shippingElement.innerText = '+ $' + shippingFee.toFixed(2);
    }

    // Update the grand total on the page
    var grandTotalElement = document.querySelector('.grand-total');
    if (grandTotalElement) {
        grandTotalElement.innerText = '$' + grandTotal.toFixed(2);
    }
}

function updateCartItem(productId, quantity, price) {

    // Send AJAX request to update item quantity in the cart
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/cart/update/' + productId, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Cart item updated successfully:', xhr.responseText);
        } else {
            console.error('Error updating cart item:', xhr.statusText);
        }
    };

    // Send data
    var data = 'quantity=' + encodeURIComponent(quantity) + '&price=' + encodeURIComponent(price);
    xhr.send(data);
}

function removeItemFromCart(productId) {

    // Send AJAX request to remove item from the cart
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/cart/remove/' + productId, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Item removed from cart:', xhr.responseText);

            // Remove the corresponding row from the cart table
            var cartRow = document.getElementById('cart-item-' + productId);
            if (cartRow) {
                cartRow.remove();
            }

            updateCartCount(-1); // Decrease cart count when the product is removed
            recalculateSubtotal(); // Update the subtotal and total values
        } else {
            console.error('Error removing item from cart:', xhr.statusText);
        }
    };

    xhr.send();
}

async function createOrder() {
    // Fetch and format the necessary data
    var grandTotalElement = document.querySelector('.grand-total');
    var grandTotal = grandTotalElement ? grandTotalElement.textContent : null;
    var couponCode = document.querySelector('input[name="coupon_code"]').value.trim();

    if (!grandTotal) {
        alert('Grand total is missing');
        return;
    }
    var grandTotalAmount = parseFloat(grandTotal.replace('$', '').trim());
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked');

    if (!paymentMethod) {
        alert('Please select a payment method.');
        return;
    }

    let couponID = null;
    if (couponCode) {
        try {
            couponID = await getCouponId(couponCode);
            console.log('Coupon ID:', couponID);
        } catch (error) {
            console.error('Error getting coupon ID:', error);
            alert(error);
            return;
        }
    }
    
    if(!couponID){
        var formData = {
            grand_total: grandTotalAmount,
            payment_method: paymentMethod.value
        };
    }else{
        var formData = {
            grand_total: grandTotalAmount,
            payment_method: paymentMethod.value,
            coupon_id: couponID
        };
        console.log('Form Data :',formData);
    }

    // Send an AJAX request
    // Send an AJAX request
    fetch('http://localhost:8080/order/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData),
    })
    .then(response => {
        console.log('Raw response:', response);

        // Check if the response is OK
        if (response.ok) {
            return response.json(); // Parse the JSON response if successful
        }

        // If not OK, return the response text for debugging
        return response.text().then(text => {
            console.error('Response not OK, raw text:', text);
            throw new Error(`Server responded with status ${response.status}`);
        });
    })
    .then(data => {
        // Handle parsed JSON response
        console.log('Parsed response:', data);

        // Redirect based on the provided URL
        if (data.url) {
            window.location.href = data.url;
        } else {
            console.error('No redirect URL provided in response:', data);
            alert('Unexpected response. Please try again later.');
        }
    })
    .catch(error => {
        // Handle network or other fetch-related errors
        console.error('Error creating order:', error);
        alert('An error occurred while processing your request. Please try again.');
    });    
}

document.addEventListener('DOMContentLoaded', function() {
    recalculateSubtotal();

    var orderBtn = document.querySelector('.place-order-btn');
    if (orderBtn) {
        orderBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            var grandTotalElement = document.querySelector('.grand-total');
            var grandTotal = grandTotalElement ? grandTotalElement.textContent : null;
            var grandTotalAmount = parseFloat(grandTotal.replace('$', '').trim());

             // First, create the order            
            // Check which payment method is selected
            var paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (paymentMethod) {
                if (paymentMethod.value === 'COD') {
                    
                    createOrder(); 
                } else if (paymentMethod.value === 'Stripe') {
                    // If 'Stripe' is selected, call the createOrder function and Stripe payment
                    createOrder();
                    $.ajax({
                        url: 'payment', // Endpoint for Stripe checkout
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            total: grandTotalAmount
                        }),
                        success: function(data) {
                            if (data.url) {
                                window.location.href = data.url; // Redirect to the Stripe checkout page
                            } else {
                                console.error('Error creating checkout session:', data.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error during payment API call:', error);
                        }
                    });
                }
            } else {
                console.error('No payment method selected');
            }
        });
    }
});

//coupons
function applyCoupon() {
    var couponCode = document.querySelector('input[name="coupon_code"]').value.trim();
    var subTotalElement = document.querySelector('.subtotal');
    var subTotal = subTotalElement ? subTotalElement.textContent : null;
    
    if (!couponCode) {
        alert("Please enter a coupon code.");
        return;
    }
    
    if (!subTotal) {
        alert("Unable to calculate discount. Sub total is missing.");
        return;
    }

    var subTotalAmount = parseFloat(subTotal.replace('$', '').trim());
    
    // Send AJAX request to validate and apply the coupon
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/product/applyCoupon', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var disc = parseFloat(response.discount);
                updateCouponBlock(disc); 
                recalculateSubtotal();
            } else {
                alert(response.message); 
            }
        } else {
            console.error('Error applying coupon:', xhr.statusText);
        }
    };

    var data = 'coupon_code=' + encodeURIComponent(couponCode) + '&sub-total=' + encodeURIComponent(subTotalAmount);
    xhr.send(data);
}

function updateCouponBlock(discount) {
    //var couponBlock = document.getElementById('coupon-block');
    var couponDiscountElement = document.getElementById('coupon-discount');

    //couponBlock.classList.remove('d-none');
    couponDiscountElement.innerText = '- $' + discount.toFixed(2); //update discount value

    setTimeout(function() {
        
        // Run your update function after DOM content is loaded and the grand total is set
        updateGrandTotalWithCoupon(discount);
    }, 100);
}

function updateGrandTotalWithCoupon(discount) {
    // Get the grand total element
    var grandTotalElement = document.querySelector('.grand-total');

    // Make sure the grand total element exists
    if (grandTotalElement) {
        // Parse the existing grand total from the element
        var grandTotal = parseFloat(grandTotalElement.innerText.replace('$', '').trim());

        // Subtract the discount from the grand total
        var newGrandTotal = grandTotal - discount;

        // Update the grand total element with the new value
        grandTotalElement.innerText = '$' + newGrandTotal.toFixed(2);

    } else {
        console.error('Grand total element not found!');
    }
}

function getCouponId(coupon) {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/product/couponID', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        resolve(response.couponID);
                    } else {
                        reject(response.message);
                    }
                } catch (e) {
                    reject('Invalid JSON response: ' + xhr.responseText);
                }
            } else {
                reject('Request failed with status: ' + xhr.status);
            }
        };

        xhr.onerror = function () {
            reject('Request error');
        };

        var data = 'coupon_code=' + encodeURIComponent(coupon);
        xhr.send(data);
    });
}