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
        <?php echo var_dump($orderItems); die(); ?>
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
                        <?php foreach ($orderItems as $orderItem): ?>
                            <tr>
                                <td><?= esc($orderItem['order_id']) ?></td>
                                <td><?= esc($orderItem['email']) ?></td>
                                <td><?= esc($orderItem['total_amount']) ?></td>
                                <td><?= esc($orderItem['created_at']) ?></td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <h3>Items</h3>
            <form action="/admin/orders/update/12345" method="post">
                <table>
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
                        <tr>
                            <td>Product B</td>
                            <td>
                                <input type="number" name="items[1][quantity]" value="1" min="1">
                            </td>
                            <td>$15.00</td>
                            <td>$15.00</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>Total: $35.00</h3>

                <label for="order-status">Order Status:</label>
                <select id="order-status" name="order_status">
                    <option value="pending" selected>Pending</option>
                    <option value="shipped">Shipped</option>
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