        <!-- Hero Start -->
        <div class="container-fluid py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-12 col-lg-7">
                        <h4 class="mb-3 text-secondary">100% Organic Foods</h4>
                        <h1 class="mb-5 display-3 text-primary">Organic Veggies & Fruits Foods</h1>
                        <div class="position-relative mx-auto">
                            <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="number" placeholder="Search">
                            <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Submit Now</button>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-5">
                        <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active rounded">
                                    <img src="img/hero-img-1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                                    <a href="<?= base_url("shop?category=1") ?>" class="btn px-4 py-2 text-white rounded">Fruits</a>
                                </div>
                                <div class="carousel-item rounded">
                                    <img src="img/hero-img-2.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                                    <a href="<?= base_url("shop?category=2") ?>" class="btn px-4 py-2 text-white rounded">Vegetables</a>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero End -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <?= esc($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?= esc($errorMessage) ?>
            </div>
        <?php endif; ?>
        <!-- Featurs Section Start -->
        <div class="container-fluid featurs py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-car-side fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Free Shipping</h5>
                                <p class="mb-0">Free on order over $20</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-user-shield fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Security Payment</h5>
                                <p class="mb-0">100% security payment</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-exchange-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>30 Day Return</h5>
                                <p class="mb-0">30 day money guarantee</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fa fa-phone-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>24/7 Support</h5>
                                <p class="mb-0">Support every time fast</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Features Section End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
            <div class="container py-5">
                <div class="tab-class text-center">
                    <div class="row g-4">
                        <div class="col-lg-4 text-start">
                            <h1>Our Organic Products</h1>
                        </div>
                        <div class="col-lg-8 text-end">
                            <ul class="nav nav-pills d-inline-flex text-center mb-5">
                                <!--ALl Products: tab-1 -->
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">All Products</span>
                                    </a>
                                </li>

                                <?php foreach($categories as $index => $category): ?>
                                <li class="nav-item">
                                    <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-<?= $index + 2; ?>">
                                        <span class="text-dark" style="width: 130px;"><?= $category["category_name"]; ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <!--All Products tab-->
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                    <?php foreach ($products as $product): ?>
                                        <?php    // Search for the product in the cart
                                            
                                            $cartItem = array_filter($cartItems, function ($item) use ($product) {
                                                return $item['product_id'] == $product['product_id'];
                                                });

                                                // Reset to get the first match
                                                $cartItem = reset($cartItem);

                                                // Determine quantity (default to 0 if not found)
                                                $quantity = $cartItem ? $cartItem['quantity'] : 0;
                                        ?>
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item">
                                                <!-- Product Image -->
                                                <?php 
                                                $productImage = array_filter($images, fn($image) => $image["product_id"] === $product["product_id"]);
                                                $productImage = reset($productImage);
                                                ?>
                                                <div class="fruite-img">
                                                    <a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>">
                                                        <img src="<?= $productImage["image_url"] ?? 'img/default-image.jpg'; ?>" class="img-fluid w-100 rounded-top" alt="">
                                                    </a>
                                                </div>

                                                <!-- Product Category -->
                                                <?php 
                                                $productCategory = array_filter($categories, fn($category) => $category["category_id"] === $product["category_id"]);
                                                $productCategory = reset($productCategory);
                                                ?>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" 
                                                    style="top: 10px; left: 10px;">
                                                    <?= $productCategory["category_name"] ?? "Uncategorized"; ?>
                                                </div>

                                                <!-- Product Details -->
                                                <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                    <a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>">
                                                        <h4><?= $product["name"]; ?></h4>
                                                    </a>
                                                    <p><?= $product["description"]; ?></p>
                                                    <div class="product-price">
                                                    
                                                        <p class="text-dark fs-5 fw-bold mb-0">$<?= $product["base_price"]; ?> / kg</p>
                                                    </div>

                                                    <div class="product-quantity">
                                                        <!-- Add to Cart Button or Quantity Control -->
                                                        <div class="cart-control-<?= $product['product_id']; ?> d-flex align-items-center">
                                                            <!-- Show/Hide Add to Cart Button based on Cart Quantity -->
                                                            <button
                                            
                                                                class="add-to-cart-btn-<?= $product['product_id']; ?> btn border border-secondary rounded-pill px-3 text-primary <?= ($quantity > 0 ? 'd-none' : ''); ?>"
                                                                onclick="addToCart(<?= $product['product_id']; ?>, 1, <?= $product['base_price']; ?>)">
                                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart 
                                                            </button>

                                                            <!-- Show/Hide Quantity Control based on Cart Quantity -->
                                                            <div class="quantity-control-<?= $product['product_id']; ?> d-flex align-items-center <?= ($quantity > 0 ? '' : 'd-none'); ?>">
                                                                <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, <?= $product['base_price']; ?>, 'decrement')">-</button>
                                                                <input type="number" value="<?= $quantity > 0 ? $quantity : 1; ?>" class="form-control quantity-input-<?= $product['product_id']; ?>" readonly />
                                                                <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, <?= $product['base_price']; ?> , 'increment')">+</button>
                                                            </div>
                                                            <!-- Inline CSS -->
                                                            <style>
                                                                .quantity-input-<?= $product['product_id']; ?> {
                                                                    font-size: 1.1rem;
                                                                    border-radius: 10px;
                                                                    text-align: center;
                                                                    margin: 5px;
                                                                    display: inline-flex;
                                                                    min-width: 50px;
                                                                }
                                                                .add-to-cart-btn-<?= $product['product_id']; ?> {
                                                                    border-radius: 50px;
                                                                    font-size: 1rem;
                                                                    transition: background-color 0.3s ease;
                                                                }
                                                                
                                                                .quantity-control-<?= $product['product_id']; ?> {
                                                                    display: flex;
                                                                    align-items: center;
                                                                }
                                                                .quantity-control-<?= $product['product_id']; ?> button {
                                                                    width: 2.5rem;
                                                                    height: 2.5rem;
     
                                                                }
                                                            </style>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Category-specific tabs -->
                        <?php foreach ($categories as $index => $category): ?>
                        <div id="tab-<?= $index + 2; ?>" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                        <?php foreach ($products as $product): ?>
                                            <!-- Check if the product belongs to the current category -->
                                            <?php if ($product["category_id"] === $category["category_id"]): ?>
                                                <?php    // Search for the product in the cart
                                            
                                                    $cartItem = array_filter($cartItems, function ($item) use ($product) {
                                                        return $item['product_id'] == $product['product_id'];
                                                        });

                                                    // Reset to get the first match
                                                    $cartItem = reset($cartItem);

                                                    // Determine quantity (default to 0 if not found)
                                                    $quantity = $cartItem ? $cartItem['quantity'] : 0;
                                                ?>
                                                <div class="col-md-6 col-lg-4 col-xl-3">
                                                    <div class="rounded position-relative fruite-item">
                                                        <!-- Product Image -->
                                                        <?php 
                                                        $productImage = array_filter($images, fn($image) => $image["product_id"] === $product["product_id"]);
                                                        $productImage = reset($productImage);
                                                        ?>
                                                        <div class="fruite-img">
                                                            <img src="<?= $productImage["image_url"] ?? 'img/default-image.jpg'; ?>" 
                                                                class="img-fluid w-100 rounded-top" alt="">
                                                        </div>

                                                        <!-- Product Category -->
                                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" 
                                                            style="top: 10px; left: 10px;">
                                                            <?= $category["category_name"]; ?>
                                                        </div>

                                                        <!-- Product Details -->
                                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                                            <a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>">
                                                                <h4><?= $product["name"]; ?></h4>
                                                            </a>
                                                            <p><?= $product["description"]; ?></p>
                                                            <div class="product-price">
                                                            
                                                                <p class="text-dark fs-5 fw-bold mb-0">$<?= $product["base_price"]; ?> / kg</p>
                                                            </div>

                                                            <div class="product-quantity">

                                                                <!-- Add to Cart Button or Quantity Control -->
                                                                <div class="cart-control-<?= $product['product_id']; ?> d-flex align-items-center">
                                                                    <!-- Show/Hide Add to Cart Button based on Cart Quantity -->
                                                                    <button
                                                    
                                                                        class="add-to-cart-btn-<?= $product['product_id']; ?> btn border border-secondary rounded-pill px-3 text-primary <?= ($quantity > 0 ? 'd-none' : ''); ?>"
                                                                        onclick="addToCart(<?= $product['product_id']; ?>, 1, <?= $product['base_price']; ?>)">
                                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart 
                                                                    </button>

                                                                    <!-- Show/Hide Quantity Control based on Cart Quantity -->
                                                                    <div class="quantity-control-<?= $product['product_id']; ?> d-flex align-items-center <?= ($quantity > 0 ? '' : 'd-none'); ?>">
                                                                        <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, <?= $product['base_price']; ?>, 'decrement')">-</button>
                                                                        <input type="number" value="<?= $quantity > 0 ? $quantity : 1; ?>" class="form-control quantity-input-<?= $product['product_id']; ?>" readonly />
                                                                        <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, <?= $product['base_price']; ?> , 'increment')">+</button>
                                                                    </div>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>      
            </div>
        </div>
        <!-- Fruits Shop End-->


        <!-- Features Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-secondary rounded border border-secondary">
                                <img src="img/featur-1.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-primary text-center p-4 rounded">
                                        <h5 class="text-white">Fresh Apples</h5>
                                        <h3 class="mb-0">20% OFF</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-dark rounded border border-dark">
                                <img src="img/featur-2.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-light text-center p-4 rounded">
                                        <h5 class="text-primary">Tasty Fruits</h5>
                                        <h3 class="mb-0">Free delivery</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-primary rounded border border-primary">
                                <img src="img/featur-3.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-secondary text-center p-4 rounded">
                                        <h5 class="text-white">Exotic Vegitable</h5>
                                        <h3 class="mb-0">Discount 30$</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Features End -->

        <!-- Vegetable Shop Start-->
        <div class="container-fluid vesitable py-5">
            <div class="container py-5">
                <h1 class="mb-0">Fresh Organic Vegetables</h1>
                <div class="owl-carousel vegetable-carousel justify-content-center">
                    <?php foreach($categories as $category): ?>
                        <?php if ($category["category_id"] == 2): ?> <!-- category is vegetables -->
                            <?php foreach ($products as $product): ?>
                                <?php if ($product["category_id"] == 2): ?> <!-- Check if the product is vegetables -->
								<?php    // Search for the product in the cart
                                            
                                            $cartItem = array_filter($cartItems, function ($item) use ($product) {
                                                return $item['product_id'] == $product['product_id'];
                                                });

                                                // Reset to get the first match
                                                $cartItem = reset($cartItem);

                                                // Determine quantity (default to 0 if not found)
                                                $quantity = $cartItem ? $cartItem['quantity'] : 0;
                                        ?>
                                    <div class="border border-primary rounded position-relative vesitable-item">
                                        <?php 
                                        // Get the product image
                                        $productImage = array_filter($images, fn($image) => $image["product_id"] === $product["product_id"]);
                                        $productImage = reset($productImage);
                                        ?>
                                        <div class="vesitable-img">
                                            <a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>">
                                                <img src="<?= $productImage['image_url'] ?? 'img/default-image.jpg'; ?>" class="img-fluid w-100 rounded-top" alt="">
                                            </a>
                                        </div>
                                        <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">
                                            <?= $category["category_name"]; ?> 
                                        </div>
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>">
                                                <h4><?= $product["name"]; ?></h4>
                                            </a>
                                            <p><?= $product["description"]; ?></p>
                                            <div class="product-price">
                                            
                                                <p class="text-dark fs-5 fw-bold mb-0">$<?= $product["base_price"]; ?> / kg</p>
                                            </div>

                                            <div class="product-quantity">
                                                <!-- Add to Cart Button or Quantity Control -->
                                                <div class="cart-control-<?= $product['product_id']; ?> d-flex align-items-center">
                                                    <!-- Show/Hide Add to Cart Button based on Cart Quantity -->
                                                    <button
                                    
                                                        class="add-to-cart-btn-<?= $product['product_id']; ?> btn border border-secondary rounded-pill px-3 text-primary <?= ($quantity > 0 ? 'd-none' : ''); ?>"
                                                        onclick="addToCart(<?= $product['product_id']; ?>, 1, <?= $product['base_price']; ?>)">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart 
                                                    </button>

                                                    <!-- Show/Hide Quantity Control based on Cart Quantity -->
                                                    <div class="quantity-control-<?= $product['product_id']; ?> d-flex align-items-center <?= ($quantity > 0 ? '' : 'd-none'); ?>">
                                                        <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, <?= $product['base_price']; ?>, 'decrement')">-</button>
                                                        <input type="number" value="<?= $quantity > 0 ? $quantity : 1; ?>" class="form-control quantity-input-<?= $product['product_id']; ?>" readonly />
                                                        <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, <?= $product['base_price']; ?> , 'increment')">+</button>
                                                    </div>
                                                    <!-- Inline CSS -->
                                                    <style>
                                                        .quantity-input-<?= $product['product_id']; ?> {
                                                            font-size: 1.1rem;
                                                            border-radius: 10px;
                                                            text-align: center;
                                                            margin: 5px;
                                                            display: inline-flex;
                                                            min-width: 50px;
                                                        }
                                                        .add-to-cart-btn-<?= $product['product_id']; ?> {
                                                            border-radius: 50px;
                                                            font-size: 1rem;
                                                            transition: background-color 0.3s ease;
                                                        }
                                                        
                                                        .quantity-control-<?= $product['product_id']; ?> {
                                                            display: flex;
                                                            align-items: center;
                                                        }
                                                        .quantity-control-<?= $product['product_id']; ?> button {
                                                            width: 2.5rem;
                                                            height: 2.5rem;

                                                        }
                                                    </style>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!-- Vegetable Shop End -->

        <!-- Banner Section Start-->
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4">
                            <h1 class="display-3 text-white">Fresh Exotic Fruits</h1>
                            <p class="fw-normal display-3 text-dark mb-4">in Our Store</p>
                            <p class="mb-4 text-dark">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic words etc.</p>
                            <a href="#" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">BUY</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="img/baner-1.png" class="img-fluid w-100 rounded" alt="">
                            <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                                <h1 style="font-size: 100px;">1</h1>
                                <div class="d-flex flex-column">
                                    <span class="h2 mb-0">50$</span>
                                    <span class="h4 text-muted mb-0">kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Section End -->


        <!-- Bestsaler Product Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                    <h1 class="display-4">Bestseller Products</h1>
                    <p>Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-1.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-2.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-3.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-4.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-5.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-6.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-1.jpg" class="img-fluid rounded" alt="">
                            <div class="py-4">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-2.jpg" class="img-fluid rounded" alt="">
                            <div class="py-4">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-3.jpg" class="img-fluid rounded" alt="">
                            <div class="py-4">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-4.jpg" class="img-fluid rounded" alt="">
                            <div class="py-2">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bestsaler Product End -->


        <!-- Fact Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="bg-light p-5 rounded">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>satisfied customers</h4>
                                <h1>1963</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>quality of service</h4>
                                <h1>99%</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>quality certificates</h4>
                                <h1>33</h1>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-3">
                            <div class="counter bg-white rounded p-5">
                                <i class="fa fa-users text-secondary"></i>
                                <h4>Available Products</h4>
                                <h1>789</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fact Start -->



