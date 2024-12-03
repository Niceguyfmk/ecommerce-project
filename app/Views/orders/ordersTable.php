<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= esc($message) ?>
    </div>
<?php endif; ?>
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
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Coupon ID</th>
                        <th>Total Amount</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= esc($order['order_id']) ?></td>
                                <td><?= esc($order['email']) ?></td>
                                <td><?= esc($order['coupon_id']) ?></td>
                                <td><?= esc($order['total_amount']) ?></td>
                                <td><?= esc($order['status']) ?></td>
                                <td><?= esc($order['created_at']) ?></td>
                                <td>
                                    <a href="<?= site_url('/order/delete/' . $order['order_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                                    <a href="<?= site_url('/order/orderDetails/' . $order['order_id']) ?>" class="btn btn-info btn-sm">Details</a>
                                    <a href="<?= site_url('/order/orderStatus/' . $order['order_id']) ?>" class="btn btn-warning btn-sm">Status</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>