<?php $user_id = $userData ['user_id']; ?>
<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="my-5 text-center py-3">Order Item Details</h3>
            <h4>Order #<?= $orderItems[0]['unique_order_id'] ?></h4>
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orderItems)): ?>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><img src="<?= base_url($item['image_url']) ?>" width="100" height="80"></td>
                                <td><?= esc($item['quantity']) ?></td>
                                <td><?= esc($item['name']) ?></td>
                                <td><?= esc($item['price']) ?></td>
                                <td>
                                    <button type="button"
                                     class="btn btn-primary btn-sm"
                                     data-bs-toggle="modal"
                                     data-bs-target="#ratingModal"
                                     data-item-id="<?= esc($item['product_id']) ?>">
                                        Rate
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No items found for this order.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Rating Modal -->
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ratingModalLabel">Rate Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" id="ratingForm">
        <div class="modal-body">
            <input type="hidden" id="product_id" name="product_id">
                <div class="mb-3 form-group">

                    <div class="star-rating" id="rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5" title="5 stars">&#9733;</label>
                        
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4" title="4 stars">&#9733;</label>
                        
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" title="3 stars">&#9733;</label>
                        
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2" title="2 stars">&#9733;</label>
                        
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1" title="1 star">&#9733;</label>
                    </div>
                </div>
                <div class="form-group">
                    <!-- ENSURE THIS IS CORRECT -->
                    <input type="hidden" 
                        id="product_id" 
                        name="product_id" 
                        value="<?= esc($item['product_id']) ?>"
                    >
                    <label for="comment" class="form-label">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Write your review..."></textarea>
                </div>
            </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const ratingModal = document.getElementById('ratingModal');
    const productIdInput = document.getElementById('product_id');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const commentInput = document.getElementById('comment');
    const saveButton = document.querySelector('#ratingModal .btn-primary');

    // Critical: Capture product ID when modal is triggered
    if (ratingModal) {
        ratingModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered modal
            const productId = button?.getAttribute('data-item-id'); // Use optional chaining

            console.group('Modal Opening Debug');
            console.log('Triggered Button:', button);
            console.log('Extracted Product ID:', productId);
            console.groupEnd();

            // Reset form fields to prevent showing old data
            resetForm();

            // Ensure product ID is set
            if (productId) {
                productIdInput.value = productId;
                console.log('Product ID Input Set To:', productIdInput.value);

                // Check if the user has already rated this product
                checkExistingRating(productId);
            } else {
                console.error('No product ID found when opening modal');
                alert('Error: Unable to identify product. Please try again.');
            }
        });
    }

    // Reset form fields
    function resetForm() {
        ratingInputs.forEach(input => {
            input.disabled = false;
            input.checked = false; // Clear previous ratings
        });
        commentInput.value = ''; // Clear previous comment
        commentInput.disabled = false;
        saveButton.disabled = false;
    }

    // Function to check if a rating already exists
    function checkExistingRating(productId) {
        // AJAX request to check if the user has already rated this product
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/product/rating/check?product_id=${productId}`, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log('Existing Review Status:', response.status);

                if (response.status === 'existing_review') {
                    // If review exists, disable rating inputs and populate the comment
                    disableRatingInputs();
                    populateExistingReview(response.rating, response.comment);
                } else {
                    // If no existing review, enable inputs
                    enableRatingInputs();
                }
            }
        };
        
        xhr.send();
    }

    // Function to disable the rating input fields
    function disableRatingInputs() {
        ratingInputs.forEach(input => {
            input.disabled = true;
        });
        commentInput.disabled = true;
        saveButton.disabled = true;     
    }

    // Function to enable the rating input fields
    function enableRatingInputs() {
        ratingInputs.forEach(input => {
            input.disabled = false;
        });
        commentInput.disabled = false;
        saveButton.disabled = false; 
    }

    // Function to populate the modal with the existing review
    function populateExistingReview(rating, comment) {
        // Set the existing rating and comment
        document.querySelector(`input[name="rating"][value="${rating}"]`).checked = true;
        commentInput.value = comment;
    }

    // Rating Submission (Save Changes Button)
    if (saveButton) {
        saveButton.addEventListener('click', () => {
            const rating = document.querySelector('input[name="rating"]:checked')?.value;
            const comment = document.getElementById('comment')?.value;
            const productId = document.getElementById('product_id')?.value;

            // Enhanced Validation
            console.group('Rating Submission Validation');
            console.log('Rating:', rating);
            console.log('Comment:', comment);
            console.log('Product ID:', productId);
            console.groupEnd();

            // Comprehensive Checks
            if (!rating) {
                alert('Please select a rating');
                return;
            }

            if (!productId) {
                alert('Unable to identify the product. Please refresh the page or contact support.');
                return;
            }

            // Existing AJAX submission logic...
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/product/rating', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function () {
                console.group('Server Response');
                console.log('Status:', xhr.status);
                console.log('Response Text:', xhr.responseText);
                console.groupEnd();

                if (xhr.status === 200) {
                    console.log('Rating submitted successfully!');
                    // Optionally close modal
                    const modalInstance = bootstrap.Modal.getInstance(ratingModal);
                    modalInstance?.hide();  // Use optional chaining to ensure modalInstance exists
                } else {
                    alert('Failed to submit rating. Please try again.');
                }
            };
            
            // Ensure correct parameter names
            const data = 
                'rating=' + encodeURIComponent(rating) + 
                '&comment=' + encodeURIComponent(comment) + 
                '&productId=' + encodeURIComponent(productId);
            
            console.log('Sending Data:', data);
            xhr.send(data);
        });
    }
});

</script>