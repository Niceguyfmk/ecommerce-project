<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="my-5 text-center py-3"><?= $heading ?></h2>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="profile-info-tab" data-bs-toggle="tab" href="#profile-info" role="tab" aria-controls="profile-info" aria-selected="true">Profile Info</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab" aria-controls="orders" aria-selected="false">Orders</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="profileTabsContent">
                <!-- Profile Info Tab -->
                <div class="tab-pane fade show active" id="profile-info" role="tabpanel" aria-labelledby="profile-info-tab">
                    <form method="post" action="<?= base_url('/user/update/' . $userData['user_id']) ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= esc($userData['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($userData['email']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password (if making any changes)">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter new password (if changing password)">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= esc($userData['address']) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('/') ?>" class="btn btn-secondary">Back to Dashboard</a>
                            <button type="submit" id="submitButton" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>

                <!-- Orders Tab -->
                <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                    <!-- Add your order list or order-related content here -->
                    <h5 class="bg-light">Bill Details</h5>
                    <?php if (empty($orders)): ?>
                        <!-- If no orders, display this message -->
                        <table class="table table-striped table-bordered table-hover" id="myTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Unique Order ID</th>
                                    <th>Item Total</th>
                                    <th>Order Status</th>
                                    <th>Order Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No orders found.</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- If there are orders, display the table -->
                        <table class="table table-striped table-bordered table-hover" id="myTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Unique Order ID</th>
                                    <th>Item Total</th>
                                    <th>Order Status</th>
                                    <th>Order Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><a href="/user/pastOrders/<?= esc($order['order_id']) ?>"><?= esc($order['unique_order_id']) ?></a></td>
                                        <td><?= esc($order['total_amount']) ?></td>
                                        <td><?= esc($order['status']) ?></td>
                                        <td><?= esc($order['created_at']) ?></td>
                                        <td><?= esc($order['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
