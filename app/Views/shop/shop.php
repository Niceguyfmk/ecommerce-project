<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <!-- Link to Home -->
        <li class="breadcrumb-item"><a href="<?= base_url("/") ?>">Home</a></li>
        
        <!-- Link to Shop, with active styling based on category presence -->
        <?php if (isset($categoryName)): ?>
            <li class="breadcrumb-item"><a href="<?= base_url("/shop") ?>">Shop</a></li>
            <li class="breadcrumb-item active text-white"><?= $categoryName; ?></li>
        <?php else: ?>
            <li class="breadcrumb-item active text-white">Shop</li>
        <?php endif; ?>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Fruits Shop Start-->
<div class="container-fluid fruite py-4">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-9"></div>
                    <div class="col-lg-3 mb-4">
                        <form method="GET" action="/shop">
                            <div class="input-group w-100 mx-auto d-flex">
                                <input type="search" name="keyword" class="form-control p-3" placeholder="search products" aria-describedby="search-icon-1">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>                    
                </div>
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4>Categories</h4>
                                    
                                    <ul class="list-unstyled fruite-categorie">
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="/shop">
                                                    <i class="fas fa-apple-alt me-2"></i>
                                                    All Products
                                                </a>                                                          
                                            </div>
                                        </li>
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="/shop?category=<?= $category['category_id']; ?>">
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
                                <h4 class="mb-3">Featured products</h4>
                                <div class="d-flex align-items-center justify-content-start">
                                    <div class="rounded me-4" style="width: 100px; height: 100px;">
                                        <img src="img/featur-1.jpg" class="img-fluid rounded" alt="">
                                    </div>
                                    <div>
                                        <h6 class="mb-2">Big Banana</h6>
                                        <div class="d-flex mb-2">
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <h5 class="fw-bold me-2">2.99 $</h5>
                                            <h5 class="text-danger text-decoration-line-through">4.11 $</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-start">
                                    <div class="rounded me-4" style="width: 100px; height: 100px;">
                                        <img src="img/featur-2.jpg" class="img-fluid rounded" alt="">
                                    </div>
                                    <div>
                                        <h6 class="mb-2">Big Banana</h6>
                                        <div class="d-flex mb-2">
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <h5 class="fw-bold me-2">2.99 $</h5>
                                            <h5 class="text-danger text-decoration-line-through">4.11 $</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-start">
                                    <div class="rounded me-4" style="width: 100px; height: 100px;">
                                        <img src="img/featur-3.jpg" class="img-fluid rounded" alt="">
                                    </div>
                                    <div>
                                        <h6 class="mb-2">Big Banana</h6>
                                        <div class="d-flex mb-2">
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star text-secondary"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <h5 class="fw-bold me-2">2.99 $</h5>
                                            <h5 class="text-danger text-decoration-line-through">4.11 $</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center my-4">
                                    <a href="#" class="btn border border-secondary px-4 py-3 rounded-pill text-primary w-100">Vew More</a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="position-relative">
                                    <img src="img/banner-fruits.jpg" class="img-fluid w-100 rounded" alt="">
                                    <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                        <h3 class="text-secondary fw-bold">Fresh <br> Fruits <br> Banner</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-4 justify-content-center">
                            <?php foreach ($products as $product): ?>
                                <?php
                                    // Search for the product in the cart
                                    $cartItem = array_filter($cartItems, function ($item) use ($product) {
                                        return $item['product_id'] == $product['product_id'];
                                    });

                                    // Reset to get the first match
                                    $cartItem = reset($cartItem);

                                    // Determine quantity (default to 0 if not found)
                                    $quantity = $cartItem ? $cartItem['quantity'] : 0;
                                ?>
                                <div class="col-md-6 col-lg-6 col-xl-4">
                                    <div class="rounded position-relative fruite-item">
                                        <?php 
                                        $productImage = array_filter($images, fn($image) => $image["product_id"] === $product["product_id"]);
                                        $productImage = reset($productImage);
                                        ?>   
                                        <div class="fruite-img">
                                            <a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>">
                                                <img src="<?= $productImage["image_url"] ?>" class="img-fluid w-100 rounded-top" alt="">
                                            </a>                                  
                                        </div>

                                        <?php
                                        $productCategory = array_filter($categories, fn($category) => $category["category_id"] === $product["category_id"]);
                                        $productCategory = reset($productCategory);
                                        ?>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?= $productCategory["category_name"] ?? "Uncategorized"; ?></div>
                                        
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4><a href="<?= base_url('/shop-detail/' . $product["product_id"]); ?>"><?= $product["name"]; ?></a></h4>
                                            <div class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                            <p><?= $product["description"]; ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0">$<?= $product["base_price"]; ?> / kg</p>

                                                <!-- Show Add to Cart Button or Quantity Control -->
                                                <div class="cart-control-<?= $product['product_id']; ?> d-flex align-items-center">
                                                    <!-- Add to Cart Button -->
                                                    <button
                                                        class="add-to-cart-btn-<?= $product['product_id']; ?> btn border border-secondary rounded-pill px-3 text-primary <?= ($quantity > 0 ? 'd-none' : ''); ?>"
                                                        onclick="addToCart(<?= $product['product_id']; ?>, 1, <?= $product['base_price']; ?>)">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </button>

                                                    <!-- Quantity Control -->
                                                    <div class="quantity-control-<?= $product['product_id']; ?> d-flex align-items-center <?= ($quantity > 0 ? '' : 'd-none'); ?>">
                                                        <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, 'decrement')">-</button>
                                                        <input type="number" value="<?= $quantity > 0 ? $quantity : 1; ?>" class="quantity-input-<?= $product['product_id']; ?> form-control" readonly />
                                                        <button class="btn btn-secondary rounded-circle" onclick="updateQuantity(<?= $product['product_id']; ?>, 'increment')">+</button>
                                                    </div>
                                                                                                                <!-- Inline CSS -->
                                                                                                                <style>
                                                                .quantity-input-<?= $product['product_id']; ?> {
                                                                    font-size: 1.1rem;
                                                                    border-radius: 10px;
                                                                    text-align: center;
                                                                    margin: 5px;
                                                                    display: inline-flex;
                                                                    width: 50px;
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

                            <!-- Pagination -->
                            <div class="col-12">
                                <div class="pagination d-flex justify-content-center mt-5">
                                    <?= $pager->links('default', 'default_full') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop End-->
