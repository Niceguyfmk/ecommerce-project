<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shop</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Shop</li>
    </ol>
</div>
<!-- Single Page Header End -->


<!-- Fruits Shop Start-->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4">
                    <div class="col-xl-3">
                        <div class="input-group w-100 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-6"></div>
                    <div class="col-xl-3">
                        <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4">
                            <label for="fruits">Default Sorting:</label>
                            <select id="fruits" name="fruitlist" class="border-0 form-select-sm bg-light me-3" form="fruitform">
                                <option value="volvo">Nothing</option>
                                <option value="saab">Popularity</option>
                                <option value="opel">Price: High to Low</option>
                                <option value="audi">Price: Low to High</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4>Categories</h4>
                                    
                                    <ul class="list-unstyled fruite-categorie">
                                    <?php foreach ($categories as $category): ?>
                                        <li>
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="#"><i class="fas fa-apple-alt me-2"></i><?= $category['category_name']; ?></a>          
                                                <?php $i = 0; ?>
                                                <?php foreach ($products as $product): 
                                                if ($product['category_id'] == $category['category_id']): 
                                                    $i++;
                                                endif;
                                                endforeach; ?>
                                                
                                                <span>(<?php echo($i); ?>)</span>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4 class="mb-2">Price</h4>
                                    <input type="range" class="form-range w-100" id="rangeInput" name="rangeInput" min="0" max="500" value="0" oninput="amount.value=rangeInput.value">
                                    <output id="amount" name="amount" min-velue="0" max-value="500" for="rangeInput">0</output>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <h4>Additional</h4>
                                    <div class="mb-2">
                                        <input type="radio" class="me-2" id="Categories-1" name="Categories-1" value="Beverages">
                                        <label for="Categories-1"> Organic</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="radio" class="me-2" id="Categories-2" name="Categories-1" value="Beverages">
                                        <label for="Categories-2"> Fresh</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="radio" class="me-2" id="Categories-3" name="Categories-1" value="Beverages">
                                        <label for="Categories-3"> Sales</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="radio" class="me-2" id="Categories-4" name="Categories-1" value="Beverages">
                                        <label for="Categories-4"> Discount</label>
                                    </div>
                                    <div class="mb-2">
                                        <input type="radio" class="me-2" id="Categories-5" name="Categories-1" value="Beverages">
                                        <label for="Categories-5"> Expired</label>
                                    </div>
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
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded position-relative fruite-item">
                                    <?php 
                                    $productImage = array_filter($images, fn($image) => $image["product_id"] === $product["product_id"]);
                                    $productImage = reset($productImage);
                                    ?>   
                                    <div class="fruite-img">
                                        <img src="<?= $productImage["image_url"] ?>" class="img-fluid w-100 rounded-top" alt="">
                                    </div>
                                    <?php
                                    $productCategory = array_filter($categories, fn($category) => $category["category_id"] === $product["category_id"]);
                                    $productCategory = reset($productCategory);
                                    ?>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?= $productCategory["category_name"] ?? "Uncategorized"; ?></div>
                                    <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                        <h4><?= $product["name"]; ?></h4>
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
                                            <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
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