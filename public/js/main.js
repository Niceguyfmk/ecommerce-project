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

    // Hide the "Add to Cart" button and show the quantity control and show card count
    document.querySelector('.add-to-cart-btn-' + productId).classList.add('d-none');
    document.querySelector('.quantity-control-' + productId).classList.remove('d-none');
    document.querySelector('.cardCount').classList.remove('d-none');
    
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

function updateQuantity(productId, action) {
    const quantityInput = document.querySelector('.quantity-input-' + productId);
    let currentQuantity = parseInt(quantityInput.value) || 1;

    if (action === 'increment') {
        currentQuantity++;
        updateCartCount(1);
    } else if (action === 'decrement' && currentQuantity > 1) {
        currentQuantity--;
        updateCartCount(-1);
    } else if (action === 'decrement' && currentQuantity === 1) {
        const addToCartBtn = document.querySelector('.add-to-cart-btn-' + productId);
        const quantityControl = document.querySelector('.quantity-control-' + productId);

        if (addToCartBtn && quantityControl) {
            addToCartBtn.classList.remove('d-none');
            quantityControl.classList.add('d-none');
        }
        removeItemFromCart(productId);
        return;
    }

    quantityInput.value = currentQuantity;

    // Update row total dynamically
    const priceElement = document.querySelector(`#cart-item-${productId} td:nth-child(3)`);
    const rowTotalElement = document.querySelector(`#cart-item-${productId} .total`);
    if (priceElement && rowTotalElement) {
        const price = parseFloat(priceElement.innerText.replace('$', '').trim());
        rowTotalElement.innerText = `${(price * currentQuantity).toFixed(2)} $`;
    }

    recalculateSubtotal();
    updateCartItem(productId, currentQuantity);  // Backend call
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
        shippingElement.innerText = '$' + shippingFee.toFixed(2);
    }

    // Update the grand total on the page
    var grandTotalElement = document.querySelector('.grand-total');
    if (grandTotalElement) {
        grandTotalElement.innerText = '$' + grandTotal.toFixed(2);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Calculate initial total and grand total on page load
    recalculateSubtotal();
});

function updateCartItem(productId, quantity) {

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
    var data = 'quantity=' + encodeURIComponent(quantity);
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










