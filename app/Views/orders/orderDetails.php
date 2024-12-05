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
                        <h2>Order Item Detail:</h2>
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
                        <th>Total Amount</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orderItems)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No orderItems found.</td>
                        </tr>
                    <?php else: ?>
                        <?php $totalAmount = $orderItems[0]['total_amount']; ?>
                        <tr>
                            <td><?= esc($orderItems[0]['order_id']) ?></td>
                            <td><?= esc($orderItems[0]['email']) ?></td>
                            <td><?= esc($orderItems[0]['total_amount']) ?></td>
                            <td><?= esc($orderItems[0]['created_at']) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h3>Items List</h3>
            <form action="<?= site_url('/order/orderUpdate/' . $orderItems[0]['order_id']) ?>" method="post">
                <table class="order-details-table table-striped table-bordered table-hover" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <thead> 
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orderItems as $orderItem): ?>
                        <tr>
                            <td><?php echo $orderItem['name']; ?></td>
                            <td><?php echo $orderItem['quantity']; ?></td>
                            <td>
                                <?php echo $orderItem['price']; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>Total: $<?= $totalAmount; ?></h3>

                <label for="order-status">Order Status:</label>
                <select id="order-status" name="order_status">
                    <option value="pending" selected>Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <div style="margin-top: 10px;">
                    <button type="submit">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>