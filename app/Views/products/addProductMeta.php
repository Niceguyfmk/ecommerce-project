        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>Product Meta Table</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= site_url('/product/metaValues/' . $product['product_id']) ?>">
                        <input type="hidden" name="product_id" value="<?= esc($product['product_id']) ?>">

                        <div id="attributes-container">
                            <div class="attribute-row mb-4">
                                <div class="row g-2">

                                    <!-- Other fields remain the same -->
                                    <div class="col-md-2">
                                        <label for="meta_key" class="form-label">Meta Key</label>
                                        <input type="text" class="form-control" name="attributes[0][meta_key]" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="meta_value" class="form-label">Meta Value</label>
                                        <input type="text" class="form-control" name="attributes[0][meta_value]" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-start mb-3">
                            <button type="button" id="add-attribute" class="btn btn-success">Add</button>
                            <button type="button" id="remove-attribute" class="btn btn-danger" disabled>Remove</button>
                            <button type="submit" class="btn btn-primary">Save</button>

                        </div>

                    </form>
                </div>
            </div>
        </div>