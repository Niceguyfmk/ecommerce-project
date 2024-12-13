<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Shop Detail</a></li>
        <li class="breadcrumb-item active text-white"><?= $product["name"] ?></li>
    </ol>
</div>
<!-- Single Page Header End -->


<!-- Single Product Start -->
<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-8 col-xl-9">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border rounded">
                            <a href="#">
                            <?php 
                            $productImage = array_filter($images, fn($image) => $image["product_id"] == $product["product_id"]);
                            $productImage = reset($productImage);
                            ?>
                                <img src="<?= base_url(relativePath: $productImage["image_url"]) ?>" class="img-fluid rounded" alt="Image">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="fw-bold mb-3"><?= $product["name"] ?></h4>
                        <?php $productCategory = array_filter($categories, fn($category) => $category["category_id"] == $product["category_id"]);
                        $productCategory = reset($productCategory);
                        ?>
                        <p class="mb-3">Category: <?= $productCategory["category_name"] ?></p>
                        <h5 class="fw-bold mb-3">$ <?= $product["base_price"] ?></h5>
                        

                        
                        <?php if (!empty($ratingsAvg)): ?>
                                <div class="d-flex mb-4">
                                        <div class="mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa fa-star <?= ($i <= $ratingsAvg) ? 'text-secondary' : '' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                </div>
                        <?php else: ?>
                            <p>No reviews yet. Be the first to leave a review!</p>
                        <?php endif; ?>


                        <p class="mb-4"><?= $product['description'] ?></p>
                        <div class="input-group quantity mb-5" style="width: 100px;">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-minus rounded-circle bg-light border" >
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm text-center border-0" value="1">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                    </div>
                    <div class="col-lg-12">
                        <nav>
                            <div class="nav nav-tabs mb-3">
                                <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                    id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                    aria-controls="nav-about" aria-selected="true">Description</button>
                                <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                    id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                    aria-controls="nav-mission" aria-selected="false">Reviews</button>
                            </div>
                        </nav>
                        <div class="tab-content mb-5">
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                
                            <?= $product['long_description'] ?>
                            <!-- Meta Values -->
                            <?php 
                            $bgClass = 'bg-light'; // Initialize the first class
                            foreach($metaValues as $metaValue): 
                            ?>
                                <div class="px-2">
                                    <div class="row g-4">
                                        <div class="col-6">
                                            <div class="row <?= $bgClass ?> align-items-center text-center justify-content-center py-2">
                                                <div class="col-6">
                                                    <p class="mb-0"><?= $metaValue['meta_key'] ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0"><?= $metaValue['meta_value'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                            $bgClass = ($bgClass === 'bg-light') ? 'bg-white' : 'bg-light'; // Toggle class
                            endforeach; 
                            ?>
                            </div>

                            <!-- Reviews Section -->
                            <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                <?php if (!empty($ratings)): ?>
                                    <?php foreach ($ratings as $review): ?>
                                        <div class="d-flex mb-4">
                                            <img src="<?= base_url('img/avatar.jpg') ?>" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                            <div class="ms-3">
                                                <p class="mb-2" style="font-size: 14px;"><?= date('F d, Y', strtotime($review['created_at'])) ?></p>
                                                <div class="d-flex justify-content-between">
                                                    <h5><?= esc($review['user_name']) ?></h5>

                                                </div>
                                                <div class="mb-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fa fa-star <?= ($i <= $review['rating']) ? 'text-secondary' : '' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <p><?= esc($review['comment']) ?></p>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No reviews yet. Be the first to leave a review!</p>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xl-3">
                <div class="row g-4 fruite">
                    <div class="col-lg-12">
                        <div class="mb-4">
                            <h4>Categories</h4>
                            
                            <ul class="list-unstyled fruite-categorie">
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <div class="d-flex justify-content-between fruite-name">
                                        <a href="/shop?category=<?= $category['category_id']; ?>"">
                                            <i class="fas fa-apple-alt me-2"></i>
                                            <?= $category['category_name']; ?>
                                        </a>                                                          
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="position-relative">
                        <img src="<?= base_url('img/banner-fruits.jpg') ?>" class="img-fluid w-100 rounded" alt="Image">
                            <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                <h3 class="text-secondary fw-bold">Fresh <br> Fruits <br> Banner</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h1 class="fw-bold mb-0">Related products</h1>
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
                                                <img src="<?= base_url($productImage['image_url'] ?? 'img/default-image.jpg') ?>" class="img-fluid w-100 rounded-top" alt="">
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
                                                <p>250g</p>
                                                <p class="text-dark fs-5 fw-bold mb-0">$<?= $product["base_price"]; ?></p>
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
    </div>
</div>
<!-- Single Product End -->
    

 