<aside id="sidebar" class="js-sidebar">
            <!-- Content For Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="<?= base_url(relativePath: 'auth/admin') ?>">Apparel Wear</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Admin Elements
                    </li>
                    <li class="sidebar-item">
                        <a href="<?= base_url(relativePath: 'auth/admin') ?>" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <?php if (isset($adminData['role_id']) && $adminData['role_id'] === "1"): ?>
                        <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-user pe-2"></i>
                            Users
                        </a>
                        
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="<?= site_url(relativePath: 'auth/register') ?>" class="sidebar-link">Add User</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?=  site_url('auth/adminList') ?>" class="sidebar-link">User List</a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#products" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-shopping-cart pe-2"></i>
                            Products
                        </a>
                        <ul id="products" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="<?=  site_url(relativePath: '/product/createProduct') ?>" class="sidebar-link">Create Product</a>
                            </li>
                            <li class="sidebar-item">
                            <a href="<?=  site_url(relativePath: '/product/viewProducts') ?>" class="sidebar-link">View Product List</a>
                            </li>

                            </li>

                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#coupons" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-tag pe-2"></i>
                            Coupons
                        </a>
                        <ul id="coupons" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="<?=  site_url(relativePath: '/product/coupons') ?>" class="sidebar-link">Coupons</a>
                            </li>
                            <li class="sidebar-item">
                            <a href="<?=  site_url(relativePath: '/product/couponsTable') ?>" class="sidebar-link">Coupons Table</a>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#orders" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-solid fa-clipboard-list pe-2"></i>
                            Orders
                        </a>
                        <ul id="orders" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="<?=  site_url(relativePath: '/order/viewOrders') ?>" class="sidebar-link">View Orders</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?=  site_url(relativePath: '/order/viewOrders') ?>" class="sidebar-link">Order Status</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="<?=  site_url(relativePath: '/order/order-tracking') ?>" class="sidebar-link">Order Tracking Home</a>
                            </li>
            
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#review" data-bs-toggle="collapse"
                            aria-expanded="false"><i class="fa-regular fa-file-lines pe-2"></i>
                            Reviews
                        </a>
                        <ul id="review" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="<?=  site_url(relativePath: '/product/ratingsTable') ?>" class="sidebar-link">Ratings Table</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </aside>