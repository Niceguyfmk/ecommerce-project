<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Product Attributes</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('/product/attributes/' . $product['product_id']) ?>">
                <input type="hidden" name="product_id" value="<?= esc($product['product_id']) ?>">

                <div id="attributes-container">
                    <?php if (!empty($productAttributes)): ?>
                        <?php foreach ($productAttributes as $index => $attribute): ?>
                            <div class="attribute-row mb-4" id="attribute-row-<?= esc($attribute['attribute_id']) ?>">
                                <!-- Dynamic Heading -->
                                <h4 class="attribute-heading py-3">Attribute <?= $index + 1 ?></h4>
                                <div class="row g-2">
                                    <!-- Attribute Name -->
                                    <div class="col-md-2 mb-4">
                                        <label for="attribute_name" class="form-label">Attribute Name</label>
                                        <select name="attributes[<?= $index ?>][attribute_id]" class="form-control" required>
                                            <?php foreach ($attributes as $availableAttribute): ?>
                                                <option value="<?= esc($availableAttribute['attribute_id']) ?>"
                                                    <?= $attribute['attribute_id'] == $availableAttribute['attribute_id'] ? 'selected' : '' ?>>
                                                    <?= esc($availableAttribute['attribute_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Unit Type -->
                                    <div class="col-md-2">
                                        <label for="unit_type" class="form-label">Unit Type</label>
                                        <select name="attributes[<?= $index ?>][unit_type]" class="form-control" required>
                                            <option value="" disabled>Select unit type</option>
                                            <?php foreach ($enumValues as $enum): ?>
                                                <option value="<?= esc($enum) ?>"
                                                    <?= $attribute['unit_type'] == $enum ? 'selected' : '' ?>>
                                                    <?= esc(ucfirst($enum)) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Unit Quantity -->
                                    <div class="col-md-2">
                                        <label for="unit_quantity" class="form-label">Unit Quantity</label>
                                        <input type="number" step="0.01" class="form-control"
                                               name="attributes[<?= $index ?>][unit_quantity]"
                                               value="<?= esc($attribute['unit_quantity']) ?>" required>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-2">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control"
                                               name="attributes[<?= $index ?>][price]"
                                               value="<?= esc($attribute['price']) ?>" required>
                                    </div>

                                    <!-- Discount Price -->
                                    <div class="col-md-2">
                                        <label for="discount_price" class="form-label">Discount Price</label>
                                        <input type="number" step="0.01" class="form-control"
                                               name="attributes[<?= $index ?>][discount_price]"
                                               value="<?= esc($attribute['discount_price']) ?>">
                                    </div>

                                    <!-- Stock -->
                                    <div class="col-md-2">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input type="number" class="form-control" name="attributes[<?= $index ?>][stock]"
                                               value="<?= esc($attribute['stock']) ?>" required>
                                    </div>

                                    <!-- Show Attribute -->
                                    <div class="col-md-2">
                                        <label for="is_default" class="form-label">Show Attribute</label>
                                        <select name="attributes[<?= $index ?>][is_default]" class="form-control" required>
                                            <option value="1" <?= $attribute['is_default'] == 1 ? 'selected' : '' ?>>Yes
                                            </option>
                                            <option value="0" <?= $attribute['is_default'] == 0 ? 'selected' : '' ?>>No
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger delete-attribute"
                                        data-attribute-id="<?= $attribute['attribute_id'] ?>">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Empty fields for adding new attributes -->
                        <div class="attribute-row mb-4">
                            <!-- Content remains the same -->
                        </div>
                    <?php endif; ?>
                </div>

                <div class="text-start mb-3">
                    <button type="button" id="add-attribute" class="btn btn-success">Add</button>
                    <button type="button" id="remove-attribute" class="btn btn-danger" disabled>Remove</button>
                    <button type="submit" class="btn btn-primary">Save Attributes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
function initializedeleteAttributeButtons() {
    // Select all delete buttons
    const deleteAttributeButtons = document.querySelectorAll('.delete-meta');

    // Add event listeners to each delete button
    deleteAttributeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const metaId = this.dataset.metaId; // Retrieve the meta_id from the button's dataset

            if (confirm('Are you sure you want to delete this meta value?')) {
                // Send an AJAX request to delete the row
                fetch(`/product/deleteAttribute/${metaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => {
                        if (response.ok) {
                            // Remove the row from the DOM
                            const rowToDelete = document.getElementById(`meta-row-${metaId}`);
                            if (rowToDelete) {
                                rowToDelete.remove();
                            }
                        } else {
                            alert('Failed to delete the meta value. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        });
    });
}

initializedeleteAttributeButtons();
</script>