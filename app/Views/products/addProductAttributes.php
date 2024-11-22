<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Product Attributes</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= site_url('/product/attributes/' . $product['product_id']) ?>">
                <input type="hidden" name="product_id" value="<?= esc($product['product_id']) ?>">

                <div id="attributes-container">
                    <div class="attribute-row mb-4">
                        <!-- Dynamic Heading -->
                        <h4 class="attribute-heading py-3">Attribute 1</h4>

                        <div class="row g-2">
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

                            <!-- Unit Type -->
                            <div class="col-md-2">
                                <label for="unit_type" class="form-label">Unit Type</label>
                                <select name="attributes[0][unit_type]" class="form-control" required>
                                    <option value="" disabled selected>Select unit type</option>
                                    <?php foreach ($enumValues as $enum): ?>
                                        <option value="<?= esc($enum) ?>"><?= esc(ucfirst($enum)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Other fields remain the same -->
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
                                <label for="is_default" class="form-label">Show Attribute</label>
                                <select name="attributes[0][is_default]" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
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