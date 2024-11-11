
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
                        <th>Name</th>
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
                                <td><?= esc($product['name']) ?></td>
                                <td><?= esc($product['base_price']) ?></td>
                                <td><?= esc($product['description']) ?></td>
                                <td>
                                    <a href="<?= site_url('product/edit/' . $product['product_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= site_url('product/delete/' . $product['product_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

