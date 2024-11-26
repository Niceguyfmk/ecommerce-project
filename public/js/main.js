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



    // Product Quantity
    $('.quantity button').on('click', function () {
        var button = $(this);
        var oldValue = button.parent().parent().find('input').val();
        if (button.hasClass('btn-plus')) {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        button.parent().parent().find('input').val(newVal);
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
    document.getElementById('add-to-cart-btn-' + productId).classList.add('d-none');
    document.getElementById('quantity-control-' + productId).classList.remove('d-none');
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
    
    // Get the current quantity input
    var quantityInput = document.getElementById('quantity-input-' + productId);
    var currentQuantity = parseInt(quantityInput.value); // Ensure this is a number

    // Perform actions based on the current state
    if (action === 'increment') {
        quantityInput.value = currentQuantity + 1;
        updateCartCount(1); // Increment the cart count
    } else if (action === 'decrement' && currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
        updateCartCount(-1); // Decrement the cart count
    } else if (action === 'decrement' && currentQuantity === 1) {
        // Hide the quantity control, card count, and show the "Add to cart" button
        document.getElementById('add-to-cart-btn-' + productId).classList.remove('d-none');
        document.getElementById('quantity-control-' + productId).classList.add('d-none');

        updateCartCount(-1); // Decrease cart count when the product is removed

        // Remove the item from the cart on the server
        removeItemFromCart(productId);
        return;
    }

    // Ensure the updated value is reflected in the input field
    quantityInput.value = Math.max(1, parseInt(quantityInput.value) || 1);

    // Send updated quantity to the server
    updateCartItem(productId, quantityInput.value);
}


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
            updateCartCount(-1); // Decrease cart count when the product is removed
        } else {
            console.error('Error removing item from cart:', xhr.statusText);
        }
    };

    xhr.send();
}







