<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\ProductCategoriesModel;
use App\Models\AttributesModel;
use App\Models\ProductAttributesModel;
use App\Models\TempCartModel;
use App\Models\ImagesModel;
use Firebase\JWT\JWT;
class Home extends BaseController
{
    //Shop related pages
    public function index(): string
    {
        
        // Retrieve the cookie value or create it if it doesn't exist
        $uid = $this->request->getCookie('uid');

        if (!$uid) {
            // If no uid cookie, generate a unique uid
            $uid = uniqid('cart_', true);
            
            // Set the cookie to expire in 1 week
            $this->response->setCookie('uid', $uid, 60 * 60 * 24 * 7); 
        } //expires in a week, 60sec * 60min=3600sec for 1 hour, so 604800sec in 1 week

        $message = session()->getFlashdata('success');  
        $errorMessage = session()->getFlashdata('error');
        $pageTitle = 'Organic Home';

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $products = $productModel->getProducts();

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();

        $tempCartModel = new TempCartModel();
        $cartItems = $tempCartModel->getTempCartItems($uid);
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/index', ['message' => $message,
           'errorMessage' => $errorMessage, 'categories' => $categories,
           'products' => $products,
           'images' => $images,
           'cartItems' => $cartItems,
           'uid' => $uid])
           .view('shop-Include/footer');       
    }

    public function shop(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Organic Shop';
        $keyword = $this->request->getGet('keyword'); // Get search keyword
        $categoryFilter = $this->request->getGet('category'); 
        $categoryName =null;

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        // If a category filter is applied, get the category name
        if ($categoryFilter) {
            $categoryName = $categoryModel->getCategoryName($categoryFilter); 
        } else {
            $categoryName = null; // No category selected
        }

        $productModel = new ProductModel();
        $products = $productModel->filterProducts($keyword, $categoryFilter);

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();

        $pager = $productModel->pager;

        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/shop', [
           'message' => $message,
           'categories' => $categories,
           'products' => $products,
           'images' => $images,
           'pager' => $pager,
           'categoryName' => $categoryName
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

    public function cart(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Cart';

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $products = $productModel->getProducts();

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/cart', ['message' => $message, 'categories' => $categories, 'products' => $products, 'images' => $images])
           .view('shop-Include/footer');
            
    }

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
