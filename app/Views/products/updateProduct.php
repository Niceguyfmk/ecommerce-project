
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
            <form method="POST" action="<?= site_url('/product/update/' . $product['product_id']) ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= esc($product['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="base_price" class="form-label">Base Price</label>
                    <input type="number" step="0.01" class="form-control" id="base_price" name="base_price" value="<?= esc($product['base_price']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Product Description</label>
                    <textarea class="form-control" id="description" name="description"><?= esc($product['description']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>
</div>

