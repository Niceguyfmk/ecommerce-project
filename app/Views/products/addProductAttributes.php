
<div class="container">
    <!-- Add a Card for the Table -->
    <div class="card">
        <div class="card-header">
            <div class="title-container">
                <div class="row justify-content-center">
                    <h2>Product Attributes:</h2>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('/product/attributes/' . $product['product_id']) ?>">
                <!-- Hidden Product ID -->
                <input type="hidden" name="product_id" value="<?= esc($product['product_id']) ?>">
                
                <!-- Attribute Name -->
                <div class="col-md-2 mb-4">
                    <label for="attribute_name" class="form-label">Attribute Name</label>
                    <select name="attributes[0][attribute_id]" class="form-control" required>
                        <?php foreach ($attributes as $attribute): ?>
                            <option value="<?= esc($attribute['attribute_id']) ?>">
                                <?= esc($attribute['attribute_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Attributes Container -->
                <div id="attributes-container">
                    <div class="attribute-row mb-4">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label for="unit_type" class="form-label">Unit Type</label>
                                <select name="attributes[0][unit_type]" class="form-control" required>
                                    <option value="gms">gms</option>
                                    <option value="ml">ml</option>
                                    <option value="ltr">ltr</option>
                                    <option value="pcs">pcs</option>
                                    <option value="count">count</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="unit_quantity" class="form-label">Unit Quantity</label>
                                <input type="number" step="0.01" class="form-control" name="attributes[0][unit_quantity]" required>
                            </div>
                            <div class="col-md-2">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" name="attributes[0][price]" required>
                            </div>
                            <div class="col-md-2">
                                <label for="discount_price" class="form-label">Discount Price</label>
                                <input type="number" step="0.01" class="form-control" name="attributes[0][discount_price]">
                            </div>
                            <div class="col-md-2">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" name="attributes[0][stock]" required>
                            </div>
                            <div class="col-md-2">
                                <label for="is_default" class="form-label">Show Attrubute</label>
                                <select name="attributes[0][is_default]" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Attribute Button -->
                <div class="text-end mb-3">
                    <button type="button" id="add-attribute" class="btn btn-success">Add Another Attribute</button>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Save Attributes</button>
            </form>
        </div>
    </div>
</div>


