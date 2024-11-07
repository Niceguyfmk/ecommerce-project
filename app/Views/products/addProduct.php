<div class="container mt-5">
    <h1>Add New Product</h1>
    <form method="POST" action="<?= site_url('product/addProduct') ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="base_price" class="form-label">Base Price</label>
            <input type="number" step="0.01" class="form-control" id="base_price" name="base_price" required>
        </div>

        <!-- Category dropdown -->
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['category_id']) ?>"><?= esc($category['category_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <h3>Product Attributes</h3>
        <?php foreach ($attributes as $attribute): ?>
            <div class="mb-3">

                <label for="attribute_<?= $attribute['attribute_id'] ?>" class="form-label"><?= esc($attribute['attribute_name']) ?></label>
                <input type="text" class="form-control" id="attribute_<?= $attribute['attribute_id'] ?>"
                 name="attributes[<?= $attribute['attribute_id'] ?>][value]" placeholder="Enter value (e.g., Red, Large)">
                
                <label for="attribute_<?= $attribute['attribute_id'] ?>_price" class="form-label">Additional Price</label>
                <input type="number" step="0.01" class="form-control" id="attribute_<?= $attribute['attribute_id'] ?>_price"
                 name="attributes[<?= $attribute['attribute_id'] ?>][additional_price]" placeholder="Enter additional price">
                
                <label for="attribute_<?= $attribute['attribute_id'] ?>_quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="attribute_<?= $attribute['attribute_id'] ?>_quantity"
                 name="attributes[<?= $attribute['attribute_id'] ?>][quantity]" placeholder="Enter quantity">

            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>
</div>
