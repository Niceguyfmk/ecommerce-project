<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="container">
    <!-- Add a Card for the Table -->
    <div class="card">
        <div class="card-header">
            <div class="title-container">
                <div class="row justify-content">
                    <div class="row">
                        <h2>Ratings Table:</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Table to display user records -->
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>User ID</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ratings)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ratings as $rating): ?>
                            <tr>
                                <td><?= esc($rating['rating_id']) ?></td>
                                <td><?= esc($rating['product_id']) ?></td>
                                <td><?= esc($rating['product_name']) ?></td>
                                <td><?= esc($rating['user_id']) ?></td>
                                <td><?= esc($rating['rating']) ?></td>
                                <td><?= esc($rating['comment']) ?></td>
                                <td><?= esc($rating['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let table = new DataTable('#myTable');

document.addEventListener('DOMContentLoaded', function () {
    // Initialize the Edit Role Modal functionality
    initializeEditRoleModal();
});
</script>