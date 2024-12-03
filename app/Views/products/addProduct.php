<div class="container mt-5 mb-5">
    <h1>Add New Product</h1>
    <form method="post" action="<?= site_url('product/addProduct') ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>

        <div class="mb-3">
            <label for="long_description" class="form-label">Long Description</label>
            <div id="editor">
                <textarea class="form-control" id= "long_description" name="long_description"></textarea>
            </div>
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

        <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>
</div>

