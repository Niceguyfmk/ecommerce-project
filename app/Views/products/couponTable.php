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
                        <h2>Coupons Table:</h2>
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
                        <th>Code</th>
                        <th>Discount Type</th>
                        <th>Discount Value</th>
                        <th>Expiry Date</th>
                        <th>Min. Order Amount</th>
                        <th>Max Usage</th>
                        <th>Max Discount Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($coupons)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($coupons as $coupon): ?>
                            <tr>
                                <td><?= esc($coupon['coupon_id']) ?></td>
                                <td><?= esc($coupon['code']) ?></td>
                                <td><?= esc($coupon['discount_type']) ?></td>
                                <td><?= esc($coupon['discount_value']) ?></td>
                                <td><?= esc($coupon['expiry_date']) ?></td>
                                <td><?= esc($coupon['min_order_amount']) ?></td>
                                <td><?= esc($coupon['max_usage']) ?></td>
                                <td><?= esc($coupon['max_discount_value']) ?></td>
                                <td>
                
                                    <a href="<?= site_url('/product/deleteCoupon/' . $coupon['coupon_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                </td>
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