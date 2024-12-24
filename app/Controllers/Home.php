<?php

namespace App\Controllers;

use App\Models\ProductMetaModel;
use App\Models\ProductRatingModel;
use App\Models\CartModel;
use App\Models\CartItemsModel;
use App\Models\OrderItemsModel;
use App\Models\ProductModel;
use App\Models\ProductCategoriesModel;
use App\Models\AttributesModel;
use App\Models\ProductAttributesModel;
use App\Models\TempCartModel;
use App\Models\ImagesModel;
use App\Models\OrdersModel;
use App\Models\UserModel;
use Firebase\JWT\JWT;
class Home extends BaseController
{
    /**
    ** 
    ** 
    ** Shop Pages
    ** 
    ** 
    **/

    
    public function index(): string
    {
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Organic Home';

        // Initialize models
        $imagesModel = new ImagesModel();
        $categoryModel = new ProductCategoriesModel();
        $productModel = new ProductModel();
        $attributesModel = new AttributesModel();
        $productAttributesModel = new ProductAttributesModel();
        $orderItemsModel = new OrderItemsModel();
        $productRatingModel = new ProductRatingModel();

        // Fetch data
        $images = $imagesModel->getAllImages();
        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getProducts();
        $attributes = $attributesModel->getAllAttributes();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
        
        // Ensure a UID cookie exists (for both logged in and guest users)
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            $uid = uniqid('cart_', true);
            $this->response->setCookie('uid', $uid, 60 * 60 * 24 * 7); // 1 week
        }

        $userData = session()->get(key: 'userData'); // Check if user is logged in

        if ($userData && isset($userData['user_id'])) {
            // User is logged in, fetch cart items from permanent CartItemsModel
            $userId = $userData['user_id'];
        
            // Fetch cart
            $cartModel = new CartModel();
            $cart = $cartModel->where('user_id', $userId)->first();
        
            // If no cart exists, create one
            if (!$cart) {
                // Insert the new cart and get the inserted ID
                $cartId = $cartModel->insert([
                    'user_id' => $userId,
                    'coupon_id' => null,  // or set any default coupon ID
                ]);
            } else {
                // If cart exists, use the existing cart_id
                $cartId = $cart['cart_id'];
            }
            $tempCartModel = new TempCartModel();
            $tempCartItems = $tempCartModel->getTempCartItems($uid);

            if (!empty($tempCartItems)) {
                log_message('error', 'No temporary cart items found for UID: ' . print_r($tempCartItems, true));
                $cartItemsModel = new CartItemsModel();
                foreach ($tempCartItems as $item) {
                    if($item['status'] === '0'){
                        $data = [
                            'cart_id' => $cartId,
                            'product_id' => $item['product_id'],
                            'product_attribute_id' => $item['product_attribute_id'],
                            'quantity' => $item['quantity'],
                            'uid' => $uid,
                            'price' => $item['price']
                        ];
                        //log_message('notice', message: 'Adding item to cart: ' . print_r($data, true));
                        // Add item to the permanent cart, ignore duplicates
                        $cartItemsModel->addCartItem($data);
                    }
                }
    
                // Update the temporary cart products status after transferring using uid
                $result = $tempCartModel->upadateStatusUsingUID($uid);
                if (!$result) {
                    log_message('error', 'Failed to update the status of temporary cart items for UID: ' . $uid);
                }
            }
        
            // Fetch cart items using the cart_id
            $cartItemsModel = new CartItemsModel();
            $cartItems = $cartItemsModel->getUserCart($cartId);
        
        } else {
            // Guest user, fetch cart items from TempCartModel
            $tempCartModel = new TempCartModel();
            $cartItems = $tempCartModel->getTempCartItems($uid);
        }       
        //average product ratings
        $avgProductRating = $productRatingModel->getProductAvgRating();

        //best selling products
        $bestSellingProducts = $orderItemsModel->getBestSellingProducts(6);

        // Load views with data
        return view('shop-Include/header', ['pageTitle' => $pageTitle])
            . view('shop/index', [
                'message' => $message,
                'errorMessage' => $errorMessage,
                'categories' => $categories,
                'products' => $products,
                'images' => $images,
                'cartItems' => $cartItems,
                'bestSellingProducts' => $bestSellingProducts,
                'avgProductRating' => $avgProductRating,
                'uid' => $uid,
                'userData'=> $userData
            ])
            . view('shop-Include/footer');  
    }
    //products page ... /shop
    public function shop(): string
    {
        // Retrieve the cookie value or create it if it doesn't exist
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            $uid = uniqid('cart_', true);
            $this->response->setCookie('uid', $uid, 60 * 60 * 24 * 7); // 1 week
        }
    
        $message = session()->getFlashdata('message');
        $pageTitle = 'Organic Shop';
        $keyword = $this->request->getGet('keyword'); // Get search keyword
        $categoryFilter = $this->request->getGet('category'); 
        $categoryName = null;
    
        // Initialize models
        $imagesModel = new ImagesModel();
        $categoryModel = new ProductCategoriesModel();
        $productModel = new ProductModel();
        $attributesModel = new AttributesModel();
        $productAttributesModel = new ProductAttributesModel();   
        $productRatingModel = new ProductRatingModel();
    
        // Fetch data
        $images = $imagesModel->getAllImages();
        $categories = $categoryModel->getAllCategories();
    
        // If a category filter is applied, get the category name
        if ($categoryFilter) {
            $categoryName = $categoryModel->getCategoryName($categoryFilter);
        }
    
        $products = $productModel->filterProducts($keyword, $categoryFilter);
        $attributes = $attributesModel->getAllAttributes();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
        $avgProductRating = $productRatingModel->getProductAvgRating();
        $pager = $productModel->pager;
    
        // Check if the user is logged in
        $userData = session()->get('userData');
        $cartItems = [];
    
        if ($userData && isset($userData['user_id'])) {
            // Logged-in user: Use CartItemsModel
            $userId = $userData['user_id'];
    
            $cartModel = new CartModel();
            $cart = $cartModel->where('user_id', $userId)->first();
    
            // If no cart exists, create one
            if (!$cart) {
                $cartId = $cartModel->insert([
                    'user_id' => $userId,
                    'coupon_id' => null,
                ], true); 
            } else {
                $cartId = $cart['cart_id'];
            }
    
            $cartItemsModel = new CartItemsModel();
            $cartItems = $cartItemsModel->getUserCart($cartId);
        } else {
            // Guest user: Use TempCartModel
            $tempCartModel = new TempCartModel();
            $cartItems = $tempCartModel->getTempCartItems($uid);
        }
    
        // Load views with data
        return view('shop-Include/header', ['pageTitle' => $pageTitle])
            . view('shop/shop', [
                'message' => $message,
                'categories' => $categories,
                'products' => $products,
                'avgProductRating' => $avgProductRating,
                'images' => $images,
                'pager' => $pager,
                'categoryName' => $categoryName,
                'cartItems' => $cartItems,
                'uid' => $uid,
                'userData' => $userData,
            ])
            . view('shop-Include/footer');
    }
    //Product Shop-Detail 
    public function detail($id): string
    {
        $message = session()->getFlashdata('success');
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Organic Shop - Detail';
    
        // Initialize models
        $imagesModel = new ImagesModel();
        $categoryModel = new ProductCategoriesModel();
        $productModel = new ProductModel();
        $attributesModel = new AttributesModel();
        $productAttributesModel = new ProductAttributesModel();
        $productRatingModel = new ProductRatingModel();
        $productMetaModel = new ProductMetaModel();
    
        // Fetch data
        $images = $imagesModel->getAllImages();
        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getProducts();
        $product = $productModel->getProduct($id);
        
        $attributes = $attributesModel->getAllAttributes();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
        $ratings = $productRatingModel->getAllProductRatings($id);
        $ratingsAvg = $productRatingModel->getAverageRating($id);
        $metaValues = $productMetaModel->getValuebyProductID($id);
        // Check if product exists
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    
        // Ensure a UID cookie exists (for both logged-in and guest users)
        $uid = $this->request->getCookie('uid');
        if (!$uid) {
            $uid = uniqid('cart_', true);
            $this->response->setCookie('uid', $uid, 60 * 60 * 24 * 7); // 1 week
        }
    
        $userData = session()->get(key: 'userData'); // Check if user is logged in
    
        if ($userData && isset($userData['user_id'])) {
            // User is logged in, fetch cart items from permanent CartItemsModel
            $userId = $userData['user_id'];
    
            // Fetch cart
            $cartModel = new CartModel();
            $cart = $cartModel->where('user_id', $userId)->first();
    
            // If no cart exists, create one
            if (!$cart) {
                $cartId = $cartModel->insert([
                    'user_id' => $userId,
                    'coupon_id' => null, // or set any default coupon ID
                ]);
                $cart = $cartModel->find($cartId);
            }
            $cartId = $cart['cart_id'];
            $cartItemsModel = new CartItemsModel();
            $cartItems = $cartItemsModel->getUserCart($cartId);
    
        } else {
            // Guest user, fetch cart items from TempCartModel
            $tempCartModel = new TempCartModel();
            $cartItems = $tempCartModel->getTempCartItems($uid);
        }
    
        // Load views with data
        return view('shop-Include/header', ['pageTitle' => $pageTitle])
            . view('shop/shop-detail', [
                'message' => $message,
                'errorMessage' => $errorMessage,
                'categories' => $categories,
                'products' => $products,
                'product' => $product,
                'metaValues' => $metaValues,
                'images' => $images,
                'ratings' => $ratings,
                'ratingsAvg' => $ratingsAvg,
                'cartItems' => $cartItems,
                'uid' => $uid,
                'userData' => $userData
            ])
            . view('shop-Include/footer');
    }
    //Customer Profile in shop
    public function profile(){
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Profile';
        $userData = session()->get('userData'); 
        $userID = $userData['user_id'];

        $ordersModel = new OrdersModel();
        $orders = $ordersModel->getOrdersByID($userID);
        
        return view('shop-include/header', ['pageTitle' => $pageTitle]) 

        . view('shop/profile', [
            "heading" => "User Profile",
            "pageTitle" => $pageTitle,
            "userData" => $userData,
            "orders" => $orders,
            "errorMessage"=> $errorMessage,
            "message"=> $message
            ])
        . view('shop-include/footer');
    }

    public function contact(){
        $pageTitle = 'Contact Us';

        return view('shop-include/header', ['pageTitle' => $pageTitle]) 
        . view('shop/contact')
        . view('shop-include/footer');
    }

    /**
    ** 
    ** 
    ** Dashboard Admin Side
    ** 
    ** 
    **/

    public function login(){
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        return view('login', ['message' => $message, 'errorMessage' => $errorMessage,]);
    }
    
    public function adminDashboard() {
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Dashboard';

        // Initialize models
        $usersModel = new UserModel;
        $imagesModel = new ImagesModel();
        $categoryModel = new ProductCategoriesModel();
        $productModel = new ProductModel();
        $attributesModel = new AttributesModel();
        $productAttributesModel = new ProductAttributesModel();
        $orderItemsModel = new OrderItemsModel();
        $ordersModel = new OrdersModel();
        $productRatingModel = new ProductRatingModel();

        // Fetch data START:

            //get admin details
            $adminData = session()->get(key: 'adminData'); // Check if user is logged in
            //total orders
            $totalOrders = $ordersModel->countAll();
            //total users
            $totalUsers = $usersModel->countAll();
            //total sales
            $totalSales = $ordersModel->getTotalSales();
            //Sales by Product
            $productData = $productModel->getProductSalesData();
            // Prepare data for the chart
            $productNames = array_column($productData, 'name');
            $productSales = array_column($productData, 'products_sold');
            // Get the revenue by category data
            $revenueByCategory = $orderItemsModel->getRevenueByCategory();
            // Prepare data for the chart
            $categories = array_column($revenueByCategory, 'category_name');
            $revenues = array_column($revenueByCategory, 'total_revenue');
            
        // Fetch data END

        return view('include/header', ['pageTitle' => $pageTitle, 'message' => $message, 'errorMessage' => $errorMessage]) 
            . view('include/sidebar', ['adminData' => $adminData]) 
            . view('include/nav') 
            . view('index', [
                'adminData' => $adminData,
                'totalOrders' => $totalOrders,
                'totalUsers' => $totalUsers,
                'totalSales' => $totalSales,
                'productNames' => json_encode($productNames),
                'productSales' => json_encode($productSales),
                'categories' => json_encode($categories),
                'revenues' => json_encode($revenues),
                ]) 
            . view('include/footer');
    }   
   
    public function register(){
        $pageTitle = 'Add User';
        $adminData = session()->get(key: 'adminData'); 

        return view('include/header', ['pageTitle' => $pageTitle])
         . view('include/sidebar', ['adminData' => $adminData])
         . view('include/nav') 
         . view('register', ['adminData' => $adminData])
         . view('include/footer');
    }
}
