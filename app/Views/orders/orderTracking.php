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
    <div class="card-header">
        <div class="title-container">
            <div class="row justify-content">
                <div class="row">
                    <h2>Orders Tracking:</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="tracking">
        <form action="<?= site_url('order/order-tracking/updateTrackingStatus') ?>" method="POST">
            <label for="order_id">Select Order</label>
            <select name="order_id" id="order_id">
                <?php foreach ($orders as $order): ?>
                    <?php if ($order['status'] !== "completed"): ?>
                    <option value="<?= $order['order_id'] ?>"><?= $order['unique_order_id'] ?> - <?= $order['total_amount'] ?></option>
                    <?php endif ?>
                <?php endforeach; ?>
            </select>

            <label for="status">Select Tracking Status</label>
            <select name="status" id="status">
                <option value="Order Shipped">Order Shipped</option>
                <option value="Out for Delivery">Out for Delivery</option>
                <option value="Order Delivered">Order Delivered</option>
                <option value="Order Failed">Order Failed</option>
            </select>

            <button type="submit" class="btn btn-warning">Update Status</button>
        </form>
    </div>
</div>

<div class="container">
    <!-- Add a Card for the Table -->
    <div class="card">
        <div class="card-header">
            <div class="title-container">
                <div class="row justify-content">
                    <div class="row">
                        <h2>Orders Table:</h2>
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
                        <th>Order Tracking ID</th>
                        <th>Order ID</th>
                        <th>Order Tracking Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ordersTracked)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ordersTracked as $track): ?>
                            <tr>
                                <td><?= esc($track['id']) ?></td>
                                <td><?= esc($track['order_tracking_id']) ?></td>
                                <td><?= esc($track['order_id']) ?></td>
                                <td><?= esc($track['order_tracking_status']) ?></td>
                                <td><?= esc($track['created_at']) ?></td>
                                <td>
                
                                    <a href="<?= site_url('/order/order-tracking/delete/' . $track['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                    <a href="<?= site_url('/order/order-tracking/timeline/' . $track['order_tracking_id']) ?>" class="btn btn-info btn-sm">Details</a>
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