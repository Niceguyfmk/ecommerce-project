<?php

namespace App\Controllers;
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
use Firebase\JWT\JWT;
class Home extends BaseController
{
    //Shop related pages
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

            //Fetch cart
            $cartModel = new CartModel();
            $cart = $cartModel->where('user_id', $userId)->first();

            // If no cart exists, create one
            if (!$cart) {
                $cart = $cartModel->insert([
                    'user_id' => $userId,
                    'coupon_id' => null,  // or set any default coupon ID
                ]);
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
            . view('shop/index', [
                'message' => $message,
                'errorMessage' => $errorMessage,
                'categories' => $categories,
                'products' => $products,
                'images' => $images,
                'cartItems' => $cartItems, // Use correct cart items based on login state
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
        $message = session()->getFlashdata('message');
        $pageTitle = 'Organic Shop-Detail';
        $keyword = $this->request->getGet('keyword'); // Get search keyword
        $categoryFilter = $this->request->getGet('category'); // Get category filter

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $product = $productModel->getProduct($id);
        $relatedProducts = $productModel->getProducts();

        // Check if product exists
        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/shop-detail', ['message' => $message, 'categories' => $categories, 'product' => $product, 'relatedProducts' => $relatedProducts,'images' => $images])
           .view('shop-Include/footer');
            
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

    /**
     * 
     * 
     * Dashboard Admin Side
     * 
     * 
    **/

    //Auth Related Pages
    public function login(){
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        return view('login', ['message' => $message, 'errorMessage' => $errorMessage,]);
    }
    
    public function adminDashboard() {
        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Dashboard';
    
        return view('include/header', ['pageTitle' => $pageTitle, 'message' => $message, 'errorMessage' => $errorMessage,]) 
            . view('include/sidebar') 
            . view('include/nav') 
            . view('index', ['message' => $message]) 
            . view('include/footer');
    }   
   
    public function register(){
        $pageTitle = 'Add User';
        return view('include/header', ['pageTitle' => $pageTitle]) . view('include/sidebar') . view('include/nav') . view('register')
         . view('include/footer');
    }
}
