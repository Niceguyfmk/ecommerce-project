<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif; ?>
<div class="container">
    <!-- Add a Card for the Table -->
    <div class="card">
        <div class="card-header">
            <div class="title-container">
                <div class="row justify-content">
                    <div class="row">
                        <h2>Products List:</h2>
                </div>
            </div>
    </div>
        </div>
        <div class="card-body">
            <!-- Table to display user records -->
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Base Price</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No products found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= esc($product['product_id']) ?></td>
                                
                                <!-- Display the image -->
                                <td>
                                    <?php
                                    // Find the corresponding image URL for the product
                                    $product_image = ''; // Default empty image URL
                                    foreach ($images as $image) {
                                        if ($image['product_id'] == $product['product_id']) {
                                            $product_image = $image['image_url'];
                                            break;
                                        }
                                    }
                                    // If an image URL is found, display the image
                                    if ($product_image):
                                    ?>
                                        <img src="<?= base_url($product_image) ?>" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                                    <?php else: ?>
                                        <span>No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($product['name']) ?></td>
                                
                                <!-- Displaying Category name -->
                                <td>
                                    <?php foreach($categories as $category){
                                        if($category['category_id'] == $product['category_id']){
                                            $category_name = $category['category_name'];
                                            break;
                                            }
                                        } 
                                    echo esc($category_name);
                                    ?>
                                </td>

                                <td><?= esc($product['base_price']) ?></td>
                                <td><?= esc($product['description']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        data-bs-toggle ="modal"
                                        data-bs-target ="#editProductModal"
                                        data-user-id ="<?= esc($product['product_id']) ?>"
                                        data-current-role-id ="<?= esc($product['product_id']) ?>"
                                        data-product-name ="<?= esc($product['name']) ?>"
                                        data-product-category = <?= esc($category_name) ?>
                                        data-product-price ="<?= esc($product['base_price']) ?>"
                                        data-product-description ="<?= esc($product['description']) ?>" class="btn btn-warning btn-sm">Edit</button>
                                        <a href="<?= site_url('/product/delete/' . $product['product_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                        <a href="<?= site_url('/product/updateAttributes/' . $product['product_id']) ?>" class="btn btn-primary btn-sm">Attributes</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Editing Role -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('product/update/' . $product['product_id']) ?>" method="post" id="productUpdateForm">
                <div class="modal-body">

                    <input type="hidden" id="productID" name="product_id">

                    <!-- Role Selection Dropdown -->
                    <div class="form-group">
                              
                    <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="name" required>

                        <label for="base_price" class="form-label">Base Price</label>
                        <input type="number" step="0.01" class="form-control" id="productBasePrice" name="base_price" required>

                        <label for="description" class="form-label">Product Description</label>
                        <textarea class="form-control" id="productDescription" name="description" required></textarea>

                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" name="category_id" required>
                            <option>Select a Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc($category['category_id']) ?>"><?= esc($category['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="image" class="form-label">Image</label>
                        <img src="<?= base_url($product_image) ?>" alt="Product Image" style="width: 100px; height: 100px; object-fit: cover;">
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

// Initialize all page-specific functionality

// Function to handle modal population for editing product
function populateProductEditModal() {
    document.querySelectorAll('.btn-warning').forEach(function(button) {
        button.addEventListener('click', function() {
            // Get data from the clicked button's data attributes
            const productId = this.getAttribute('data-user-id');
            const productName = this.getAttribute('data-product-name');
            const productCategory = this.getAttribute('data-product-category');
            const productPrice = this.getAttribute('data-product-price');
            const productDescription = this.getAttribute('data-product-description');
            const productImage = this.getAttribute('data-product-image'); // if you have this as a data attribute

            // Populate modal fields with the product data
            document.getElementById('productID').value = productId;
            document.getElementById('productName').value = productName;
            document.getElementById('productBasePrice').value = productPrice;
            document.getElementById('productDescription').value = productDescription;
            document.getElementById('productCategory').value = productCategory;
        });
    });
}

// Call the function for populating the product modal (used in your page)
populateProductEditModal();

});
</script>

